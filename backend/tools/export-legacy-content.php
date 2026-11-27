<?php
declare(strict_types=1);

// Offline conversion of checked-in editorial content. Does not call legacy APIs.
$root = dirname(__DIR__, 2);
$legacy = is_file($root.'/About.php') ? $root : $root.'/backend/legacy';
libxml_use_internal_errors(true);

function document(string $html): DOMDocument
{
    $doc = new DOMDocument();
    $doc->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
    return $doc;
}

function textOf(string $value): string
{
    return trim((string) preg_replace('/\s+/u', ' ', $value));
}

function nodeToData(DOMNode $node): array|string|null
{
    if ($node instanceof DOMText) {
        return (string) preg_replace('/\s+/u', ' ', $node->textContent);
    }
    if (!$node instanceof DOMElement || !in_array($node->tagName, [
        'section','header','div','article','aside','h1','h2','h3','h4','h5','p','ul','ol','li',
        'table','thead','tbody','tr','th','td','strong','b','em','i','span','small','blockquote',
        'a','img','br','hr','details','summary','dl','dt','dd','time',
    ], true)) {
        return null;
    }
    $attrs = [];
    foreach (['id','href','src','alt','datetime','colspan','rowspan'] as $attr) {
        if ($node->hasAttribute($attr)) {
            $value = $node->getAttribute($attr);
            if (in_array($attr, ['href','src']) && preg_match('~^(?:javascript:|data:|//)~i', $value)) {
                continue;
            }
            $attrs[$attr] = $value;
        }
    }
    $children = [];
    foreach ($node->childNodes as $child) {
        $value = nodeToData($child);
        if ($value !== null) {
            $children[] = $value;
        }
    }
    if (!$children && !$attrs && !in_array($node->tagName, ['br','hr'], true)) {
        return null;
    }
    return [$node->tagName, $attrs ? $attrs : new stdClass(), $children];
}

function outputJson(string $file, array $data): void
{
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR).PHP_EOL);
}

$articles = [
    'about' => 'About.php', 'start-here' => 'StartHere.php', 'contact' => 'Contact.php',
    'privacy-policy' => 'Privacy-Policy.php', 'cookie-policy' => 'Cookie-Policy.php',
    'terms-of-service' => 'Terms-of-Service.php', 'disclaimer' => 'Disclaimer.php',
    'thank-you' => 'ThankYou.php', 'thanks' => 'Thanks.php', 'webinar' => 'webinar_index.php',
];
$pages = [];
$oldDirectory = getcwd();
chdir($legacy);
foreach ($articles as $slug => $source) {
    ob_start();
    (static fn (string $file) => require $file)($legacy.'/'.$source);
    $html = ob_get_clean();
    if (!preg_match('~<main\b[^>]*>(.*)</main>~is', $html, $main)) {
        throw new RuntimeException('Main content not found in '.$source);
    }
    $doc = document('<div id="export-root">'.$main[1].'</div>');
    $body = $doc->getElementById('export-root');
    $pages[$slug] = [
        'title' => textOf($doc->getElementsByTagName('h1')->item(0)?->textContent ?? str_replace('-', ' ', $slug)),
        'content' => nodeToData($body)[2] ?? [],
    ];
}
chdir($oldDirectory);
outputJson($root.'/frontend/src/content/pages.json', $pages);

require_once $legacy.'/components/service-catalog.php';
outputJson($root.'/backend/resources/catalog/services.json', phelon_services());

$intakeSource = file_get_contents($legacy.'/Intake.php');
preg_match("~<form id='readinessForm'[^>]*>(.*?)</form>~s", $intakeSource, $form);
$doc = document($form[1]);
$xpath = new DOMXPath($doc);
$titles = [
    'Contact Information', 'Consultation Purpose', 'Current Situation', 'Important Dates',
    'Areas of Concern', 'Attorney & Professional Support', 'Impact Details', 'Consultation Goals',
    'Readiness Self-Assessment', 'Optional Uploads', 'Disclaimer & Consent',
];
$steps = [];
foreach ($titles as $index => $title) {
    $fields = [];
    $step = $index + 1;
    foreach ($xpath->query('//*[@data-step="'.$step.'"]//*[self::input or self::select or self::textarea][@name]') as $element) {
        $name = $element->getAttribute('name');
        $type = $element->tagName === 'input' ? ($element->getAttribute('type') ?: 'text') : $element->tagName;
        if ($type === 'file' || ($type === 'hidden' && !str_starts_with($name, 'score_'))) {
            continue;
        }
        $condition = $xpath->query("ancestor::*[@id='dateDetails' or @id='importantDateDetails' or @id='deadlineFields']", $element)->length > 0
            || in_array($name, ['deadline_date','deadline_type','important_date'], true);
        $label = ($element->parentNode instanceof DOMElement && $element->parentNode->tagName === 'label')
            ? $element->parentNode->textContent
            : $xpath->query('preceding-sibling::label[1]', $element)->item(0)?->textContent;
        if ($type === 'hidden') {
            $type = 'select';
            $label = $xpath->query('preceding-sibling::*[contains(@class, "rating-card-label")]', $element)->item(0)?->textContent;
        }
        $choice = ($type === 'checkbox' || $type === 'radio') && !str_starts_with($name, 'consent_');
        $option = $element->getAttribute('value') ?: textOf($label ?? '');
        if ($choice && isset($fields[$name])) {
            $fields[$name]['options'][] = [$option, textOf($label ?? $option)];
            continue;
        }
        $label = $choice ? ucwords(str_replace('_', ' ', $name)) : textOf($label ?? ucfirst(str_replace('_', ' ', $name)));
        $field = ['name'=>$name, 'type'=>$type, 'label'=>$label, 'required'=>$element->hasAttribute('required')
            || in_array($name, ['concerns','consultation_goals'], true)];
        if ($condition) {
            $field['when'] = ['has_important_date', 'Yes'];
        }
        if ($choice) {
            $field['options'] = [[$option, textOf($element->parentNode->textContent)]];
        } elseif ($type === 'select') {
            $field['options'] = [];
            foreach ($element->getElementsByTagName('option') as $optionElement) {
                $nameOfOption = textOf($optionElement->textContent);
                $value = $optionElement->hasAttribute('value') ? $optionElement->getAttribute('value') : $nameOfOption;
                if ($value !== '') {
                    $field['options'][] = [$value, $nameOfOption];
                }
            }
            if (str_starts_with($name, 'score_')) {
                $field['options'] = array_map(static fn ($value) => [(string) $value, (string) $value], range(1, 5));
            }
        }
        $fields[$name] = $field;
    }
    $steps[] = ['title' => $title, 'fields' => array_values($fields)];
}
preg_match_all("~triggers:\\[(.*?)\\],field:'([^']+)',question:'([^']+)'~", $intakeSource, $matches, PREG_SET_ORDER);
foreach ($matches as $match) {
    preg_match_all("~'([^']+)'~", $match[1], $triggers);
    $steps[6]['fields'][] = ['name'=>$match[2], 'type'=>'select', 'label'=>$match[3], 'required'=>true,
        'options'=>[['Yes','Yes'],['No','No'],['Not sure','Not sure']], 'concerns'=>$triggers[1]];
}
outputJson($root.'/backend/resources/catalog/intake.json', $steps);
