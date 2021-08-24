/**
 * MAGIX CMS
 * @category   banner
 * @package    plugins
 * @copyright  MAGIX CMS Copyright (c) 2009 - 2013 Gerits Aurelien,
 * http://www.magix-cms.com, http://www.magix-cjquery.com, http://www.magix-dev.be
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    2.0
 * @author Gérits Aurélien <aurelien[at]magix-cms.com>|<contact[at]magix-dev.be>
 * banner
 *
 */
var banner = (function ($) {
    'use strict';
    /**
     * @param input
     */
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#preview').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
            $('#preview').removeClass('no-img').addClass('preview');
            $('#drop-zone').removeClass('no-img');
        }
    }

    function initDropZone() {
        let dropZoneId = "drop-zone";
        let buttonId = "clickHere";
        let mouseOverClass = "mouse-over";
        let dropZone = $("#" + dropZoneId);
        let btnSend = dropZone.find('button[type="submit"]');
        let ooleft = dropZone.offset().left;
        let ooright = dropZone.outerWidth() + ooleft;
        let ootop = dropZone.offset().top;
        let oobottom = dropZone.outerHeight() + ootop;
        let inputFile = dropZone.find('input[type="file"]');
        document.getElementById(dropZoneId).addEventListener("dragover", function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.addClass(mouseOverClass);
            var x = e.pageX;
            var y = e.pageY;

            if (!(x < ooleft || x > ooright || y < ootop || y > oobottom)) {
                inputFile.offset({ top: y - 15, left: x - 100 });
            } else {
                inputFile.offset({ top: -400, left: -400 });
            }

        }, true);

        if (buttonId !== "") {
            let clickZone = $("#" + buttonId);
            let oleft = clickZone.offset().left;
            let oright = clickZone.outerWidth() + oleft;
            let otop = clickZone.offset().top;
            let obottom = clickZone.outerHeight() + otop;

            console.log(clickZone);
            clickZone.mousemove(function (e) {
                console.log('test');
                let x = e.pageX;
                let y = e.pageY;
                if (!(x < oleft || x > oright || y < otop || y > obottom)) {
                    inputFile.offset({ top: y - 15, left: x - 160 });
                } else {
                    inputFile.offset({ top: -400, left: -400 });
                }
            });
        }

        dropZone.find('input[type="file"]').change(function(){
            let inputVal = $(this).val();
            if(inputVal === '') {
                $(btnSend).prop('disabled',true);
            } else {
                $(btnSend).prop('disabled',false);
            }
        });

        document.getElementById(dropZoneId).addEventListener("drop", function (e) {
            $("#" + dropZoneId).removeClass(mouseOverClass);
        }, true);
    }

    return {
        run: function() {
            $(function() {
                $( 'a.toggleModal' ).click(function(){
                    if($(this).attr('href') != '#'){
                        var id = $(this).attr('href').slice(1);

                        $('#id').val(id);
                    }
                });

                $(".ui-sortable").sortable({
                    items: "> tr",
                    placeholder: "ui-state-highlight",
                    cursor: "move",
                    axis: "y",
                    update: function () {
                        var serial = $(".ui-sortable").sortable('serialize');
                        $.jmRequest({
                            handler: "ajax",
                            url: '/admin/index.php?controller=banner&tabs=banner&action=order',
                            method: 'post',
                            data: serial,
                            successParams: function (e) {
                                $.nicenotify.initbox(e, {
                                    display: false
                                });
                            }
                        });
                    }
                });
                $(".ui-sortable").disableSelection();
            });
        },
        /*runEdit: function() {
            var defaultLabel = $('span#input-label').text();

            $('.inputfile').each(function() {
                var label	 = $('span#input-label'),
                    labelVal = label.innerHTML;

                $(this).on( 'change', function( e )
                {
                    var fileName = e.target.value;

                    if( fileName != '' ) {
                        label.text(fileName);
                        if($(this).hasClass('inputpdf')) {
                            $(this).next('label').addClass('filled').find('.fa-inverse').toggleClass('fa-upload').toggleClass('fa-file-pdf-o');
                        }
                    }
                    else {
                        label.text(labelVal);
                    }
                });

                // Firefox bug fix
                $(this).on( 'focus', function(){ $(this).addClass( 'has-focus' ); });
                $(this).on( 'blur', function(){ $(this).removeClass( 'has-focus' ); });
            });

            $("#img").change(function(){
                readURL(this);
                if(typeof $('.resetImg') !== 'undefined') {
                    $('.resetImg').removeClass('hide');
                }
            });

            $('.resetImg').click(function(e){
                e.preventDefault();
                $(this).addClass('hide');
                $("#img").val('');
                $('#preview').attr('src', '#').addClass('no-img').removeClass('preview');
                $( 'span#input-label' ).text(defaultLabel);
                return false;
            });

            initDropZone();
        }*/
    };
})(jQuery);