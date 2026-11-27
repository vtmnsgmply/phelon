import { createElement } from 'react'
import { Link } from 'react-router-dom'
import pages from '../content/pages.json'
import { Prose } from '../components/Prose'
import { Toc } from '../components/Toc'
import { usePageMeta } from '../hooks/usePageMeta'
import { legacyHref } from '../lib/legacyHref'
import { readPolicy } from '../lib/policy'

function render(node, index) {
  if (typeof node === 'string') return node.trim() ? node : ' '
  const [tag, attrs, children] = node
  if (tag === 'a') {
    const to = legacyHref(attrs.href)
    return to.startsWith('/') ? <Link to={to} key={index}>{children.map(render)}</Link> : <a href={to} key={index}>{children.map(render)}</a>
  }
  if (tag === 'img') return null
  const props = { key: index }
  if (attrs.id) props.id = attrs.id
  if (attrs.datetime) props.dateTime = attrs.datetime
  if (attrs.colspan) props.colSpan = attrs.colspan
  if (attrs.rowspan) props.rowSpan = attrs.rowspan
  if (tag === 'br' || tag === 'hr') return createElement(tag, props)
  const parts = ['table', 'thead', 'tbody', 'tr', 'ul', 'ol', 'dl'].includes(tag)
    ? children.filter(child => typeof child !== 'string' || child.trim()) : children
  return createElement(tag, props, parts.map(render))
}

export function Legal({ slug }) {
  const { title, intro, blocks, toc } = readPolicy(pages[slug])
  usePageMeta(title)
  return <div className="ph-section ph-section--compact"><div className="ph-container ph-legal">
    <Prose className="ph-legal-head">
      <h1>{title}</h1>
      {intro.map(render)}
    </Prose>
    <aside><Toc items={toc} /></aside>
    <Prose className="ph-legal-body">
      {blocks.map(render)}
    </Prose>
  </div></div>
}
