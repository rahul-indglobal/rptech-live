var config = {
    paths: {
        slick:             'js/slick.min',
        slimscroll:        'js/jquery.slimscroll.min',
        bootstrap:         'js/bootstrap.min',
        wow:               'js/wow.min',
        nouislider:        'js/nouislider.min',
        elevateZoom:       'js/jquery.elevatezoom.min',
        fancybox:          'js/jquery.fancybox.min',
        mCustomScrollbar:             'js/jquery.mCustomScrollbar.min',
        dataTables:  'js/jquery.dataTables.min',
        responsivejquerydatatables:  'js/dataTables.responsive.min',
        jRespond:  'js/jRespond.min',
        slimscrollCustom:  'js/main',
        custom: 'js/custom'
    },
    shim: {
        slick: {
            deps: ['jquery']
        },
        slimscroll: {
            deps: ['jquery']
        },
        bootstrap: {
            deps: ['jquery']
        },
        wow: {
            deps: ['jquery']
        },
        nouislider: {
            deps: ['jquery']
        },
        elevateZoom: {
            deps: ['jquery']
        },
        fancybox: {
            deps: ['jquery']
        },
        mCustomScrollbar: {
            deps: ['jquery']
        },
        dataTables: {
            deps: ['jquery']
        },
        responsivejquerydatatables: {
            deps: ['jquery','dataTables']
        },
        jRespond: {
            deps: ['jquery']
        },
        slimscrollCustom: {
            deps: ['jquery']
        },
        custom: {
            deps: ['jquery', 'bootstrap', 'fancybox', 'nouislider', 'slick', 'slimscroll', 'elevateZoom', 'dataTables', 'responsivejquerydatatables']
        },
    },
    map: {
        '*': {
            'datatables.net': 'dataTables'
        }
    }
};