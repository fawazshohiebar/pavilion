---
id: 8ab494c3-b905-44b4-b2f1-ec25f4fe8bed
blueprint: page
title: 'Parking Information'
fine_seo_is_title_custom: false
page_builder:
  -
    type: set
    attrs:
      id: mex42dr9
      values:
        type: html_block
        available_on:
          - production
          - local
        code:
          code: |-
            <style>
              #patronage {
            	display:none;
              }
            </style>
          mode: htmlmixed
  -
    type: paragraph
    attrs:
      textAlign: left
  -
    type: set
    attrs:
      id: mewjpqb6
      values:
        type: parking_information_sec_version
        style_config_field:
          padding: small
          color: white
          size: normal
          variant: no_image
        heading:
          text: 'Parking Options'
          size: small
          color: primary
          tag: h2
          uppercase: false
        parking_cards:
          -
            id: mewkdktk
            parking_heading:
              text: 'Standard Parking'
              size: small
              color: black
              tag: p
              uppercase: false
            parking_pricing:
              text: '<b>and this is bold text</b>'
              size: xsmall
              color: black
              tag: h1
              uppercase: true
            buttons:
              -
                id: mews32n3
                dynamic_button:
                  button: 'https://ecom.webhost.skidata.com/ecom/portal/home/adihex_parking/#/products'
                  text: 'Secure Standard Parking For Car Parks A & B'
                  button_options:
                    style: filled
                    color: primary
                    size: small
                    new_tab: true
                    icon: lock-closed
                    icon_position: start
                type: button
                enabled: true
            description: |-
              Standard Parking is available at ADNEC Car Parks A and B for AED 20.

              Parking is on a first-come, first-served basis – we recommend arriving early.

              **Parking details**
              - Price: AED 20 per vehicle (pay onsite) or buy online to save the hassle by clicking <a href="https://ecom.webhost.skidata.com/ecom/portal/home/adihex_parking/#/products">here</a>.
              - ADIHEX Entrance Tickets: Not included – tickets must be purchased separately

              Please review the parking <a href="https://www.adnec.ae/en/terms-and-conditions" target="_blank">Terms &amp; Conditions</a> before your visit

              **Directions**

              Please book your parking and find your preferred parking location below.
            second_set_buttons:
              -
                id: mewkm8xr
                dynamic_button:
                  button: null
                  text: 'Car Park A'
                  button_options:
                    style: outline
                    color: primary
                    size: small
                    new_tab: false
                    icon: map-pin
                    icon_position: start
                type: button
                enabled: true
              -
                id: mewkmmyy
                dynamic_button:
                  button: null
                  text: 'Car Park B'
                  button_options:
                    style: outline
                    color: primary
                    size: small
                    new_tab: true
                    icon: map-pin
                    icon_position: start
                type: button
                enabled: true
            type: new_set
            enabled: true
            bandar: hello
            parking_prices: '<b>AED 20 </b> <span class="text-[18px]">/ Vehicle</span>'
            card_color: white
            buttons_v2:
              -
                id: mews30hg
                text: 'Car Park A'
                text_color: '#1e2939'
                before_icon: map-pin
                after_icon: arrow-right
                background_color: '#F5F5F1'
                type: new_set
                enabled: true
                button: 'https://goo.gl/maps/xE3kYSnrSB7jAE8g6'
              -
                id: mewtido8
                text: 'Car Park B'
                text_color: '#1e2939'
                before_icon: map-pin
                after_icon: arrow-right
                background_color: '#F5F5F1'
                type: new_set
                enabled: true
                button: 'https://goo.gl/maps/MwF11vzQm1mWVFDf7'
          -
            id: mewkymwe
            parking_heading:
              text: 'Premium Parking'
              size: small
              color: black
              tag: p
              uppercase: false
            parking_pricing:
              text: 'AED 20'
              size: small
              color: black
              tag: p
              uppercase: true
            description: |-
              Avoid the hassle and secure your spot by booking Premium Parking in advance.

              Enjoy guaranteed convenience with easy access to ADIHEX via the Grandstand or Central Plaza parking areas.

              **Parking details**
              - Price: AED 350 per vehicle
              - Includes: One-time access to either Grandstand or Central Plaza parking
              - QR Code: Sent to your email after purchase – required for entry
              - ADIHEX Entrance Tickets: Not included – tickets must be purchased separately

              **Directions**
            second_set_buttons:
              -
                id: mewkm8xr
                dynamic_button:
                  button: null
                  text: 'Car Park A'
                  button_options:
                    style: outline
                    color: primary
                    size: small
                    new_tab: false
                    icon: map-pin
                    icon_position: start
                type: button
                enabled: true
              -
                id: mewkmmyy
                dynamic_button:
                  button: null
                  text: 'Car Park B'
                  button_options:
                    style: outline
                    color: primary
                    size: small
                    new_tab: true
                    icon: map-pin
                    icon_position: start
                type: button
                enabled: true
            type: new_set
            enabled: true
            parking_prices: '<b>AED 350 </b> <span class="text-[18px]">/ Vehicle</span>'
            card_color: '#f5f5f1'
            buttons:
              -
                id: mews9fw1
                dynamic_button:
                  button: 'https://business.mamopay.com/pay/adnecgroup-9a34b2'
                  text: 'Secure Premium Parking'
                  button_options:
                    style: filled
                    color: primary
                    size: small
                    new_tab: false
                    icon: lock-closed
                    icon_position: start
                type: button
                enabled: true
            buttons_v2:
              -
                id: mews9kov
                text: 'Central Plaza Parking'
                type: new_set
                enabled: true
                text_color: '#1e2939'
                before_icon: map-pin
                after_icon: arrow-right
                background_color: '#FFF'
                button: 'https://maps.app.goo.gl/QJnWp4a6oqM15XZW6'
              -
                id: mewtk0vi
                text: 'Grandstand Parking'
                type: new_set
                enabled: true
                text_color: '#1e2939'
                before_icon: map-pin
                after_icon: arrow-right
                background_color: '#FFF'
                button: 'https://maps.app.goo.gl/3H9c3Nj3KW45Z4y4A'
          -
            id: mewm7mh2
            parking_heading:
              text: 'Additional Parking'
              size: small
              color: black
              tag: p
              uppercase: false
            parking_pricing:
              text: 'AED 20'
              size: small
              color: black
              tag: p
              uppercase: true
            description: |-
              Additional Parking is available at ADNEC Car Parks D and E — and it's free!

              Parking is free of charge and available on a first-come, first-served basis — so we still recommend arriving early to secure your spot.

              **Parking details**
              - ADIHEX Entrance Tickets: Please note that parking does not include entrance tickets.
              - Tickets must be purchased separately.
              - Before Your Visit: Make sure to review the General Terms & Conditions related to parking and entry.

              Please review the parking <a href="https://www.adnec.ae/en/terms-and-conditions" target="_blank">Terms & Conditions</a> before your visit 

              **Directions**
            second_set_buttons:
              -
                id: mewkm8xr
                dynamic_button:
                  button: null
                  text: 'Car Park A'
                  button_options:
                    style: outline
                    color: primary
                    size: small
                    new_tab: false
                    icon: map-pin
                    icon_position: start
                type: button
                enabled: true
              -
                id: mewkmmyy
                dynamic_button:
                  button: null
                  text: 'Car Park B'
                  button_options:
                    style: filled
                    color: primary
                    size: small
                    new_tab: true
                    icon: null
                    icon_position: start
                type: button
                enabled: true
            type: new_set
            enabled: true
            parking_prices: '<b>Free</b>'
            card_color: white
            buttons:
              -
                id: mews9nfx
                dynamic_button:
                  button: null
                  text: 'Pay Onsite'
                  button_options:
                    style: filled
                    color: secondary
                    size: small
                    new_tab: false
                    icon: null
                    icon_position: start
                type: button
                enabled: false
            buttons_v2:
              -
                id: mews9qeh
                text: 'Car Park D'
                type: new_set
                enabled: true
                text_color: '#1e2939'
                before_icon: map-pin
                after_icon: arrow-right
                background_color: '#F5F5F1'
                button: 'https://maps.app.goo.gl/KnovzM548A9zKwvW7'
              -
                id: mewt1y3a
                text: 'Car Park E'
                type: new_set
                enabled: true
                text_color: '#1e2939'
                before_icon: map-pin
                after_icon: arrow-right
                background_color: '#F5F5F1'
                button: 'https://maps.app.goo.gl/ZbNct3o5BnnCuBXN7'
        style_config:
          padding: small
          color: transparent
          size: normal
          variant: no_image
  -
    type: set
    attrs:
      id: mewu35n6
      values:
        type: call_to_action
        style_config:
          padding: small
          color: lite-secondary
          size: normal
          variant: no_image
        heading:
          text: 'Online Parking Booking'
          size: small
          color: black
          tag: p
          uppercase: false
        description: |-
          When booking online, simply register your license plate and a QR code will be sent to your email for hassle-free entry and exit at ADNEC parking facilities.

          **How to Enter Your License Plate:**
          Abu Dhabi: Category 50, number 12345 → enter 5012345
          Dubai: Category A, number 12345 → enter A12345
          Sharjah: Category 1, number 12345 → enter 112345

          **Important Notes:**
          The QR code is mandatory for accessing the parking facilities.
          ADIHEX entrance tickets are not included with parking reservations and must be purchased separately.
fine_seo_title: 'Parking Information'
fine_seo_preview: 'Parking Information'
feature_banner_image: banners/banner-homepage_adnec.webp
template: default
header_scripts:
  code: null
  mode: htmlmixed
body_start_scripts:
  code: null
  mode: htmlmixed
body_end_scripts:
  code: null
  mode: htmlmixed
updated_by: ac775259-f1c4-4a12-b768-668149cb0e1a
updated_at: 1756541354
---
