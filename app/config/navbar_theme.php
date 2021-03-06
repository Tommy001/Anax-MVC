<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',
 
    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'home'  => [
            'text'  => 'Tema',
            'url'   => $this->di->get('url')->create('theme'),
            'title' => 'Tema'
        ],
        // This is a menu item
        'regioner'  => [
            'text'  => 'Regioner',
            'url'   => $this->di->get('url')->create('regioner'),
            'title' => 'Regioner'
        ],   
        // This is a menu item
        'grid'  => [
            'text'  => 'Rutnät',
            'url'   => $this->di->get('url')->create('grid'),
            'title' => 'Rutnät'
        ],      
        // This is a menu item
        'typography'  => [
            'text'  => 'Typografi',
            'url'   => $this->di->get('url')->create('typography'),
            'title' => 'Typografi'
        ],         
        // This is a menu item
        'fontawesome'  => [
            'text'  => 'Font Awesome',
            'url'   => $this->di->get('url')->create('fontawesome'),
            'title' => 'Font Awesome'
        ],           
        // This is a menu item
        'me'  => [
            'text'  => 'Me-sidan',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Till min me-sida'
        ],           
  
        /* This is a menu item
        'test'  => [
            'text'  => 'Submenu',
            'url'   => $this->di->get('url')->create('submenu'),
            'title' => 'Submenu with url as internal route within this frontcontroller',

            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'item 0'  => [
                        'text'  => 'Item 0',
                        'url'   => $this->di->get('url')->create('submenu/item-0'),
                        'title' => 'Url as internal route within this frontcontroller'
                    ],

                    // This is a menu item of the submenu
                    'item 2'  => [
                        'text'  => '/humans.txt',
                        'url'   => $this->di->get('url')->asset('/humans.txt'),
                        'title' => 'Url to sitespecific asset',
                        'class' => 'italic'
                    ],

                    // This is a menu item of the submenu
                    'item 3'  => [
                        'text'  => 'humans.txt',
                        'url'   => $this->di->get('url')->asset('humans.txt'),
                        'title' => 'Url to asset relative to frontcontroller',
                    ],
                ],
            ],
        ],
 
        // This is a menu item 
        
        'redovisning' => [
            'text'  =>'Redovisning',
            'url'   => $this->di->get('url')->create('redovisning'),
            'title' => 'Min redovisningssida',
            'mark-if-parent-of' => 'redovisning',
        ],
        
        'dicegame' => [
            'text'  =>'Tärningsspel',
            'url'   => $this->di->get('url')->create('dicegame'),
            'title' => 'Tärningsspelet 100',
            'mark-if-parent-of' => 'dicegame',
        ],        
        
        'source' => [
            'text'  =>'Källkod',
            'url'   => $this->di->get('url')->create('source'),
            'title' => 'Källkoden till alla sidor',
            'mark-if-parent-of' => 'source',
        ],        

/*        // This is a menu item
        'om' => [
            'text'  =>'Om',
            'url'   => $this->di->get('url')->create('om'),
            'title' => 'Internal route within this frontcontroller'
        ], */

    ],
 


    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($this->di->get('request')->getCurrentUrl($url) == $this->di->get('url')->create($url)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
