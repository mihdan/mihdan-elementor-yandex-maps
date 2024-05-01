=== Mihdan: Elementor Yandex Maps ===
Contributors: mihdan
Tags: elementor, yandex, maps, api, mihdan
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://www.kobzarev.com/donate/
Requires at least: 5.8
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.6.11

Yandex Maps widget for Elementor

== Description ==
SEO-friendly Yandex Maps widget for Elementor. Easily add multiple address pins onto the same map with support for different map types (Road Map/Satellite/Hybrid/Terrain) and custom map style. Freely edit info window content of your pins with the standard Elementor text editor. And many more custom map options.

[youtube https://www.youtube.com/watch?v=UYAeDlxz9xs]

Based on the original [Elementor Google Map Extended](https://wordpress.org/plugins/extended-google-map-for-elementor/) plugin by InternetCSS.

== Installation ==
1. Visit ‘Plugins > Add New’
2. Search for ‘Yandex Maps for Elementor’
3. Activate Yandex Maps for Elementor from your Plugins page.
4. [Optional] Configure Yandex Maps for Elementor settings.

== Frequently Asked Questions ==

= Как получить ключ API =

Получить API-ключ можно в [Кабинете разработчика](https://developer.tech.yandex.ru/services/). Нажмите «Получить ключ», затем выберите сервис «JavaScript API и HTTP Геокодер» и заполните анкету — ваш API-ключ будет сразу готов к использованию.

== Changelog ==

= 1.6.11 (01.05.2024) =
* Fixed link to the plugin settings page
* Added support for Elementor 3.21+
* Added support for Elementor Pro 3.21+
* Hard dependency on Elementor plugin has been added to the plugin

= 1.6.10 (12.04.2024) =
* Added the ability to filter pins by the terms of the selected taxonomy
* Added a new filter `mihdan_elementor_yandex_maps_posts_args`
* Added support for PHP 8.3+
* Updated WPTRT library

= 1.6.9 (21.03.2024) =
* Added support for Elementor 3.20+
* Added support for Elementor Pro 3.20+
* Added the ability to completely disable lazy map loading

= 1.6.8 (24.01.2024) =
* Added ability to automatic reinitialize maps after Ajax-requests

= 1.6.7 (23.01.2024) =
* Added the ability to use a map in Loop Builder

= 1.6.6 (07.12.2023) =
* Added support for WordPress 6.4+
* Added support for Elementor 3.18+
* Added support for Elementor Pro 3.18+
* Added support for PHP 8.2+
* Added ability to save map state and share it with others

= 1.6.5 (05.05.2023) =
* Disabled lazy loading of the map in edit mode
* Fixed a bug when calculating desktop breakpoint
* Code refactoring

= 1.6.4 (04.05.2023) =
* Fixed a bug when calculating custom breakpoints
* Code refactoring

= 1.6.3 (03.05.2023) =
* Added the ability to set different height on devices
* Added the ability to set different zoom levels on devices
* Added the ability to set height dynamically
* Added the ability to set zoom level dynamically
* Fixed PHP notices

= 1.6.2 (30.04.2023) =
* Added ability to disable balloon panel on small screens
* Fixed an error setting the maximum width of the balloon

= 1.6.1 (28.04.2023) =
* Fixed an Autoptimize plugin compatibility bug

= 1.6.0 (27.04.2023) =
* Added support for WordPress 6.2+
* Added support for Elementor 3.13+
* Added support for Elementor Pro 3.13+
* Added support for PHP 8+
* Added the ability to insert shortcodes into the contents of a balloon (e.g., `[audio]`)

= 1.5.1 (19.01.2023) =
* Fixed a bug in displaying the custom marker

= 1.5.0 (19.01.2023) =
* Added lazy loading for maps
* Added a link to the settings page in the list of plugins
* Code refactoring

= 1.4.5 (24.12.2022) =
* Added support for WordPress 6.1+
* Added support for Elementor 3.9+

= 1.4.4 (06.05.2021) =
* Added support for WordPress 5.7+
* Added support for Elementor 3.3+
* Fixed bug with address searching
* Code refactoring

= 1.4.3 (29.01.2021) =
* Added support for WordPress 5.6+
* Added auto deploy script

= 1.4.2 (30.06.2020) =
* Fixed bug with manual inserting pins
* Fixed bug with coordinates inserted via Pods Framework

= 1.4.1 (16.06.2020) =
* Added dynamic tag for icon image
* Fixed bugs

= 1.4.0 (12.06.2020) =
* Added support for CPT markers
* Fixed bugs

= 1.3.6 (27.05.2020) =
* WPCS
* Added border-radius support for maps
* Added custom marker for maps. Thanks to @land0r
* Added custom icon for widget

= 1.3.5 (26.05.2020) =
* Added dynamic tags for icon and hint caption and content

= 1.3.4 (29.02.2020) =
* Fixed bug with map center search
* Refactoring JS

= 1.3.3 (01.11.2019) =
* Added filters to maps: grayscale, sepia, green, blue
* Added asynchronous script loading
* Added resource hints like prefetch, preload, preconnect
* Added notifications
* Added support for WordPress 5.3
* Added dynamic tags to latitude & longitude for pins
* Fixed bugs

= 1.3.2 (09.10.2019) =
* Added language switcher for maps
* Fixed bugs
* Removed unused code
* WPCS

= 1.3.1 (05.08.2019) =
* Bug with missing pins fixed

= 1.3 (12.07.2019) =
* Added clusterization feature
* Added clusterization settings
* Added cluster color dropdown
* Added icon colors
* Added icon types
* Added hint content
* Added icon caption
* Added icon content
* Added balloon footer
* Added property `balloonIsOpen` for balloon
* Fixed bugs
* Code converted to OOP
* Code refactored to conform WordPress Coding Standards
* JS developed according to ECMA-6 script standards

= 1.2.4 (09.07.2019) =
* Added dynamic tags to latitude & longitude

= 1.2.3 (18.01.2019) =
* Fixed bugs with translations

= 1.2.2 (18.01.2019) =
* Added settings page
* Fixed bugs

== Screenshots ==
1. Map appearance
2. Admin settings appearance
