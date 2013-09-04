services:
  jlbs.admin.navbar:
    class: %mopa_bootstrap.navbar.generic%
    arguments:
      # first argument: a named array of menues:
      - { leftmenu: '@jlbs.backend.navbar.menu=', rightmenu: '@jlbs.backend.navbar.user_menu='}
      # second argument: a named array of FormType Classes
      - { }
      # third argument: a named array of options
      - { title: "Title Site", titleRoute: "jlbs_backend_dashboard_index", fixedTop: true, isFluid: true, template:MopaBootstrapBundle:Navbar:navbar.html.twig }
    tags:
      # The alias is used to retrieve the navbar in templates
      - { name: mopa_bootstrap.navbar, alias: backendNavbar }

  jlbs.backend.navbar_builder:
      class: My\BackendBundle\Menu\Builder # change for you class menu builder
      arguments: [ '@knp_menu.factory', '@security.context' ]

  jlbs.backend.navbar.menu:
      class: Knp\Menu\MenuItem # the service definition requires setting the class
      factory_service: jlbs.backend.navbar_builder
      factory_method: mainMenu
      arguments: ["@request"]
      scope: request # needed as we have the request as a dependency here
      tags:
          - { name: knp_menu.menu, alias: examplemain } # The alias is what is used to retrieve the menu

  jlbs.backend.navbar.user_menu:
      class: Knp\Menu\MenuItem # the service definition requires setting the class
      factory_service: jlbs.backend.navbar_builder
      factory_method: createRightSideDropdownMenu
      arguments: ["@request"]
      scope: request # needed as we have the request as a dependency here
      tags:
          - { name: knp_menu.menu, alias: exampledropdown } # The alias is what is used to retrieve the menu


          