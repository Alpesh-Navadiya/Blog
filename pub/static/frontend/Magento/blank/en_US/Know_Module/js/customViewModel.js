define(['jquery', 'uiComponent', 'ko'], function ($, Component, ko) {
        'use strict';
        return Component.extend({
            heading : 'Ko',
            counter : ko.observable(0),
            subject :ko.observable('Ko subject Observable...'),
            desc : ko.observable('<sup>This Observable...</sup> is <b>contnet</b> of  knockout <sub>js</sub>js.This is content'),
            navSectionClass: 'nav-sections',
            url:'https://www.youtube.com/watch?v=H5lrjOlRTbc&list=PLztuWPhbUpTgLD_qy5ghFEVIrpRzuwsNs&index=2',
            content : '<sup>This</sup> is <b>contnet</b> of  knockout <sub>js</sub>js',
            getCustomText: function () {
                return 'This text is coming from custom text method';
            },
            getPrice : function (){
                return 250;
            },
            initialize: function () {
                this._super();
                this.subject('Updating Subject..');
                this.subject.subscribe(function(newValue){
                    this.counter(newValue.length)
                },this);

                this.desc.subscribe(function(newValue){
                 alert('my Lord..'+ newValue + 'Subject here' + this.subject());
                },this);

                this.desc.extend({
                    notify: 'always'
                });

                let self = this;
                setTimeout(function(){
                    self.desc('Change Heading..').subject('New subject Change');
                    alert('ok');
                },5000)
            },
            onChangeInput : function(){
                let inputVal = document.getElementById('custom_input').value;
                this.subject(inputVal);

            }
        });
    }
);
