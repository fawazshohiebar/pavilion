---
id: 3b8833f8-7ff4-4191-a6b8-bb94ada3ceb1
blueprint: page
title: 'Parking Information'
template: default
fine_seo_is_title_custom: false
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
updated_at: 1760605339
page_builder:
  -
    type: set
    attrs:
      id: mgf5wq03
      values:
        type: heading_and_grid
        grid_structure: v2
        design: centered
        heading:
          -
            type: heading
            attrs:
              level: 2
            content:
              -
                type: text
                marks:
                  -
                    type: bold
                text: 'Parking Information'
        description:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'Visitor parking is available at '
              -
                type: text
                marks:
                  -
                    type: bold
                text: 'Car Park A'
              -
                type: text
                text: ' and '
              -
                type: text
                marks:
                  -
                    type: bold
                text: 'Car Park B'
              -
                type: text
                text: '. Parking is paid and operates on a first-come, first-served basis.'
        replicating_grid:
          -
            id: mgf5x3ge
            heading: 'Car Park A'
            sub_heading:
              -
                type: paragraph
                content:
                  -
                    type: text
                    text: 'Conveniently located close to the '
                  -
                    type: text
                    marks:
                      -
                        type: bold
                    text: 'Aloft Hotel'
                  -
                    type: text
                    text: ', with easy access to the ICC.'
              -
                type: paragraph
                content:
                  -
                    type: text
                    marks:
                      -
                        type: bold
                    text: 'Entrance:'
                  -
                    type: text
                    text: ' President Joko Widodo Street.'
            icon_or_button: buttonlist
            button_list:
              -
                id: mgf5xja1
                label: 'Get Directions'
                design: Orangenogradient
                open_new_tab: false
                type: button_set
                enabled: true
                icon: arrow-up-right
            type: grid_item
            enabled: true
          -
            id: mgf5xvzd
            heading: 'Car Park B'
            sub_heading:
              -
                type: paragraph
                content:
                  -
                    type: text
                    text: 'Closest to the '
                  -
                    type: text
                    marks:
                      -
                        type: bold
                    text: 'Andaz Capital Gate Hotel'
                  -
                    type: text
                    text: ' and within a short walk to the venue.'
              -
                type: paragraph
                content:
                  -
                    type: text
                    marks:
                      -
                        type: bold
                    text: 'Entrance:'
                  -
                    type: text
                    text: ' Al Multaqa Street.'
            icon_or_button: buttonlist
            button_list:
              -
                id: mgf5yhnp
                label: 'Get Direction'
                design: Orangenogradient
                open_new_tab: false
                type: button_set
                enabled: true
                icon: arrow-up-right
            type: grid_item
            enabled: true
        background: bgcolor
        background_color: light-secondary
  -
    type: set
    attrs:
      id: mgf6w2pa
      values:
        type: heading_comp
        background: bgcolor
        background_color: light-secondary
        design: centered
        heading:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'Parking is '
              -
                type: text
                marks:
                  -
                    type: bold
                text: paid
              -
                type: text
                text: '. Please review the  '
              -
                type: text
                marks:
                  -
                    type: link
                    attrs:
                      href: google
                      rel: null
                      target: null
                      title: null
                  -
                    type: bold
                text: 'Parking Terms & Conditions'
              -
                type: text
                text: ' for full details.'
  -
    type: paragraph
    attrs:
      textAlign: left
fine_seo_title: 'Parking Information'
fine_seo_preview: 'Parking Information'
---
