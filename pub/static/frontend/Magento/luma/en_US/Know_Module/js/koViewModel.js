define(['jquery', 'uiComponent', 'ko'], function ($, Component, ko) {
        'use strict';
        return Component.extend({
            'defaults': {
                title: 'default title in viewmodel js file',
                content: '<b>Welcome</b> to my dummy content from viewmodel js file',
                btnText: 'Kout button',
                isPrimary: true,
            },
            initialize: function () {
                this._super();
                alert('call');

            }
        });
    }
);
