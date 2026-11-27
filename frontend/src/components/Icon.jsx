import { Briefcase, CalendarCheck, Check, ChevronDown, ChevronLeft, ChevronRight, CircleCheck, Clock, Compass, Copy,
  Globe, House, Info, Lock, Mail, Menu, ShieldCheck, Sprout, TriangleAlert, Upload, Wallet, X } from 'lucide-react'

const icons = {
  alert: TriangleAlert, briefcase: Briefcase, calendarCheck: CalendarCheck, check: Check, chevronDown: ChevronDown,
  chevronLeft: ChevronLeft, chevronRight: ChevronRight, close: X, clock: Clock, compass: Compass, copy: Copy,
  globe: Globe, home: House, info: Info, lock: Lock, mail: Mail, menu: Menu, shield: ShieldCheck, sprout: Sprout,
  success: CircleCheck, upload: Upload, wallet: Wallet,
}

export function Icon({ name, size = 22 }) {
  const Glyph = icons[name]
  return Glyph ? <Glyph size={size} strokeWidth={1.5} aria-hidden="true" focusable="false" /> : null
}
