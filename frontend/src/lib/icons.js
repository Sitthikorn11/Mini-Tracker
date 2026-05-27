import { 
  BanknotesIcon, ShoppingBagIcon, TruckIcon, CakeIcon, DocumentTextIcon,
  HomeIcon, HeartIcon, SparklesIcon, BriefcaseIcon, AcademicCapIcon,
  BoltIcon, FireIcon, TicketIcon, PhoneIcon, VideoCameraIcon
} from '@heroicons/vue/24/solid'

export const availableIcons = {
  BanknotesIcon, ShoppingBagIcon, TruckIcon, CakeIcon, DocumentTextIcon,
  HomeIcon, HeartIcon, SparklesIcon, BriefcaseIcon, AcademicCapIcon,
  BoltIcon, FireIcon, TicketIcon, PhoneIcon, VideoCameraIcon
}

export const getIconComponent = (iconName) => {
  return availableIcons[iconName] || null
}
