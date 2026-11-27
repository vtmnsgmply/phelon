// Turns the converted policy markup into blocks and a table of contents. Words are never changed:
// only decorative emoji, the old breadcrumb and the old "quick navigation" list are left out.
const DECORATION = /(?![©®™])\p{Extended_Pictographic}|️|‍|✓/gu

export const plain = node => typeof node === 'string' ? node : node[2].map(plain).join('')
const tidy = text => text.replace(DECORATION, '').replace(/\s+/g, ' ').trim()
const find = (node, tag) => typeof node === 'string' ? null : node[0] === tag ? node : node[2].reduce((hit, child) => hit || find(child, tag), null)
const links = node => typeof node === 'string' ? [] : node[0] === 'a' ? [node[1].href || ''] : node[2].flatMap(links)

function isQuickNav(node) {
  if (typeof node === 'string' || node[0] === 'section') return false
  const hrefs = links(node)
  return hrefs.length > 2 && hrefs.every(href => href.startsWith('#'))
}

function clean(node) {
  if (typeof node === 'string') {
    const text = node.replace(DECORATION, '')
    return !text.trim() && node.trim() ? null : text
  }
  if (isQuickNav(node)) return null
  const children = node[2].map(clean).filter(child => child !== null)
  if (!children.length && !['br', 'hr'].includes(node[0])) return null
  return [node[0], node[1], children]
}

// Wrapper divs around the sections are removed so each section is its own block.
const unwrap = node => typeof node !== 'string' && node[0] === 'div' && node[2].some(child => child[0] === 'section')
  ? node[2].flatMap(unwrap) : [node]

const slug = text => text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')

export function readPolicy(page) {
  const header = page.content.find(node => find(node, 'h1'))
  const title = tidy(plain(find(header, 'h1')))
  const intro = header[2].filter(node => typeof node !== 'string' && node[0] === 'p').map(clean).filter(Boolean)
  const toc = []
  const blocks = page.content.filter(node => node !== header && typeof node !== 'string').flatMap(unwrap)
    .map(clean).filter(node => node && typeof node !== 'string')
    .map(block => {
      // Heading levels used in this block are renumbered from h2 so none are skipped.
      const levels = ['h2', 'h3', 'h4'].filter(tag => find(block, tag))
      let used = false
      const walk = node => {
        if (typeof node === 'string') return node
        let [tag, attrs, children] = node
        if (levels.includes(tag)) tag = 'h' + (2 + levels.indexOf(tag))
        if (tag === 'h2') {
          const label = tidy(plain(node))
          const id = !used && block[1].id ? block[1].id : slug(label)
          used = true
          toc.push({ id, label })
          if (id !== block[1].id) attrs = { ...attrs, id }
        }
        return [tag, attrs, children.map(walk)]
      }
      return walk(block)
    })
  return { title, intro, blocks, toc }
}
