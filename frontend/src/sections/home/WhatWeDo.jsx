import { Link } from 'react-router-dom'
import { Picture } from '../../components/Picture'

export function WhatWeDo() {
  return <section id="about" className="ph-section" aria-labelledby="what-title">
    <div className="ph-container ph-split ph-split--wide-end">
      <div className="ph-sticky ph-stack-lg">
        <h2 id="what-title">Readiness support, not legal representation</h2>
        <div className="ph-photo ph-photo--wide"><Picture name="founders" alt="Two members of the PHELONPRENEUR Network standing together" sizes="(min-width: 56em) 30rem, 90vw" /></div>
      </div>
      <div className="ph-stack">
        <p className="ph-lede">We help you organize your life, protect stability, and manage the human and logistical realities that serious charges bring.</p>
        <p>Your attorney handles the legal case. We focus on the household, the practical decisions, and the plans you and your family need around it.</p>
        <div><Link className="ph-link" to="/about">About The PHELONPRENEUR Network</Link></div>
      </div>
    </div>
  </section>
}
