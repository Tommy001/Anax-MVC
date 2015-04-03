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
            'text'  => 'Start',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Min Me-sida'
        ],
        'redovisning' => [
            'text'  =>'Redovisning',
            'url'   => $this->di->get('url')->create('redovisning'),
            'title' => 'Min redovisningssida',
            'mark-if-parent-of' => 'redovisning',
            
            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'kmom01'  => [
                        'text'  => 'Kmom01',
                        'url'   => $this->di->get('url')->asset('redovisning/#kmom01'),
                        'title' => 'Kursmoment 1',
                        'class' => 'small',
                    ],
                    // This is a menu item of the submenu
                    'kmom02'  => [
                        'text'  => 'Kmom02',
                        'url'   => $this->di->get('url')->asset('redovisning/#kmom02'),
                        'title' => 'Kursmoment 2',
                        'class' => 'small',                        
                    ],   
                    'kmom03'  => [
                        'text'  => 'Kmom03',
                        'url'   => $this->di->get('url')->asset('redovisning/#kmom03'),
                        'title' => 'Kursmoment 3',
                        'class' => 'small',                        
                    ],                    
                    'kmom04'  => [
                        'text'  => 'Kmom04',
                        'url'   => $this->di->get('url')->asset('redovisning/#kmom04'),
                        'title' => 'Kursmoment 4',
                        'class' => 'small',                        
                    ],  
                    'kmom05'  => [
                        'text'  => 'Kmom05',
                        'url'   => $this->di->get('url')->asset('redovisning/#kmom05'),
                        'title' => 'Kursmoment 5',
                        'class' => 'small',                        
                    ],   
                    'kmom06'  => [
                        'text'  => 'Kmom06',
                        'url'   => $this->di->get('url')->asset('redovisning/#kmom06'),
                        'title' => 'Kursmoment 6',
                        'class' => 'small',                        
                    ],                     
                ],
            ],            
        ],
        
        'mymodule' => [
            'text'  =>'mymodule',
            'url'   => $this->di->get('url')->create('mymodule'),
            'title' => 'Min modul',
            'mark-if-parent-of' => 'mymodule',
        ],    
    

        'theme' => [
            'text'  =>'Mitt tema',
            'url'   => $this->di->get('url')->create('theme'),
            'title' => 'Min tema-sida',
            'mark-if-parent-of' => 'theme',       
 
            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'regioner'  => [
                        'text'  => 'Regioner',
                        'url'   => $this->di->get('url')->asset('regioner'),
                        'title' => 'Regioner',
                        'class' => 'small',                        
                    ],
                    // This is a menu item of the submenu
                    'grid'  => [
                        'text'  => 'Rutnät',
                        'url'   => $this->di->get('url')->asset('grid'),
                        'title' => 'Visa alla användare',
                        'class' => 'small',                        
                    ],                    

                    // This is a menu item of the submenu
                    'typgraphy'  => [
                        'text'  => 'Typografi',
                        'url'   => $this->di->get('url')->asset('typography'),
                        'title' => 'Typografi',
                        'class' => 'small',                        
                    ],
                    // This is a menu item of the submenu
                    'fontawesome'  => [
                        'text'  => 'Font Awesome',
                        'url'   => $this->di->get('url')->asset('fontawesome'),
                        'title' => 'Font Awesome',
                        'class' => 'small',                        
                    ],                    
                ],
            ],
        ],

        'list'  => [
            'text'  => 'Användare',
            'url'   => $this->di->get('url')->create('list'),
            'title' => 'Test av basklass för databasdrivna modeller',

            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'list'  => [
                        'text'  => 'Visa alla',
                        'url'   => $this->di->get('url')->create('list'),
                        'title' => 'Visa alla användare',
                        'class' => 'small',                        
                    ],                    

                    // This is a menu item of the submenu
                    'active'  => [
                        'text'  => 'Visa aktiva',
                        'url'   => $this->di->get('url')->create('users/active'),
                        'title' => 'Visa aktiva användare',
                        'class' => 'small',                        
                    ],
                    // This is a menu item of the submenu
                    'inactive'  => [
                        'text'  => 'Visa inaktiva',
                        'url'   => $this->di->get('url')->create('users/inactive'),
                        'title' => 'Visa inaktiva användare',
                        'class' => 'small',                        
                    ],                    

                    // This is a menu item of the submenu
                    'setup'  => [
                        'text'  => 'Setup',
                        'url'   => $this->di->get('url')->create('setup'),
                        'title' => 'Återställ databasen',
                        'class' => 'small',                        
                    ],
                ],
            ],
         ],

        // This is a menu item */
        
 
        
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
        ],
*/
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
