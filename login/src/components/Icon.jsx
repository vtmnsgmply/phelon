import { Briefcase, Calendar, Copy, FileText, Mail, PencilLine, Save, Upload, Download, History, ArrowLeftRight, CircleDollarSign, CreditCard, Hourglass, CalendarClock, CalendarDays, Check, ChevronDown, ChevronLeft, ChevronRight, CircleCheck, ClipboardList, Clock, ContactRound, Database, Eye, EyeOff, Globe, Info, KeyRound,
  LayoutDashboard, Lock, LogOut, Menu, Plus, Printer, RefreshCw, Search, Settings, ShieldCheck, Trash2, TriangleAlert, UserRound, Users, X } from 'lucide-react'

const icons = {
  account: UserRound, alert: TriangleAlert, copy: Copy, mail: Mail, open: PencilLine, save: Save, upload: Upload, audit: History, download: Download, finance: CircleDollarSign, transactions: ArrowLeftRight, payments: CreditCard, pending: Hourglass, contracts: FileText, services: Briefcase, bookings: CalendarClock, calendar: Calendar, migrations: Database, schedule: Clock, settings: Settings, check: Check, chevronDown: ChevronDown, clients: ContactRound, close: X, eye: Eye, eyeOff: EyeOff, globe: Globe,
  holidays: CalendarDays, info: Info, intakes: ClipboardList, key: KeyRound, lock: Lock, menu: Menu, next: ChevronRight, overview: LayoutDashboard,
  plus: Plus, previous: ChevronLeft, print: Printer, refresh: RefreshCw, search: Search, shield: ShieldCheck, signOut: LogOut,
  success: CircleCheck, team: Users, trash: Trash2,
}

export function Icon({ name, size = 20 }) {
  const Glyph = icons[name]
  return Glyph ? <Glyph size={size} strokeWidth={1.6} aria-hidden="true" focusable="false" /> : null
}
