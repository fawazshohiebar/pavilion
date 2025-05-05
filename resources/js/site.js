import Alpine from 'alpinejs'
 
import Precognition from 'laravel-precognition-alpine';
import intersect from '@alpinejs/intersect'
import collapse from '@alpinejs/collapse'
 
window.Alpine = Alpine;

Alpine.plugin(Precognition);
Alpine.plugin(intersect);
Alpine.plugin(collapse);

Alpine.start();