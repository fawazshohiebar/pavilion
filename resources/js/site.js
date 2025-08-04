import Alpine from 'alpinejs'
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
 
import Precognition from 'laravel-precognition-alpine';
import intersect from '@alpinejs/intersect'
import collapse from '@alpinejs/collapse'
import '@tailwindplus/elements';
 
window.Alpine = Alpine;
window.Swiper = Swiper;

Alpine.plugin(Precognition);
Alpine.plugin(intersect);
Alpine.plugin(collapse);

Alpine.start();