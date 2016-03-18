/*
 * jQuery Bootstrap News Box v1.0.1
 * 
 * Copyright 2014, Dragan Mitrovic
 * email: gagi270683@gmail.com
 * Free to use and abuse under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */

//Utility
if (typeof Object.create !== 'function') {
    //Douglas Crockford inheritance function
    Object.create = function (obj) {
        function F() { };
        F.prototype = obj;
        return new F();
    };
}

(function ($, w, d, undefined) {

    var NewsBox = {

        init: function ( options, elem ) {
            //cache the references
            var self = this;
            self.elem = elem;
            self.$elem = $( elem );
            
            self.newsTagName = self.$elem.find(":first-child").prop('tagName');
            self.newsClassName = self.$elem.find(":first-child").attr('class');

            self.timer = null;
            self.resizeTimer = null; // used with window.resize event 
            self.animationStarted = false;
            self.isHovered = false;


            if ( typeof options === 'string' ) {
                //string was passed
                if(console) {
                    console.error("String property override is not supported");
                }
                throw ("String property override is not supported");
            } else {
                //object was passed
                //extend user options overrides
                self.options = $.extend( {}, $.fn.bootstrapNews.options, options );
                
                self.prepareLayout();


                //autostart animation
                if(self.options.autoplay) {
                    self.animate();
                }

                if ( self.options.navigation ) {
                    self.buildNavigation();
                }

                //enable users to override the methods
                if( typeof self.options.onToDo === 'function') {
                    self.options.onToDo.apply(self, arguments);
                }

            }
        },

        prepareLayout: function() {
            var self = this;

            //checking mouse position
                
            $(self.elem).find('.'+self.newsClassName).on('mouseenter', function(){
                self.onReset(true);
            });

            $(self.elem).find('.'+self.newsClassName).on('mouseout', function(){
                self.onReset(false);
            });

            //set news visible / hidden
            $.map(self.$elem.find(self.newsTagName), function(newsItem, index){
                if(index > self.options.newsPerPage - 1) {
                    $(newsItem).hide();
                } else {
                    $(newsItem).show();
                }
            });

            //prevent user to select more news that it actualy have

            if( self.$elem.find(self.newsTagName).length < self.options.newsPerPage ) {
                self.options.newsPerPage = self.$elem.find(self.newsTagName).length;
            }
            
            //get height of the very first self.options.newsPerPage news
            var height = 0;

            $.map(self.$elem.find(self.newsTagName), function( newsItem, index ) {
                if ( index < self.options.newsPerPage ) {
                    height = parseInt(height) + parseInt($(newsItem).height()) + 10;
                }
            });

            $(self.elem).css({"overflow-y": "hidden", "height": height});

            //recalculate news box height for responsive interfaces
            $( w ).resize(function() {
                if ( self.resizeTimer !== null ) {
                    clearTimeout( self.resizeTimer );
                }
                self.resizeTimer = setTimeout( function() {
                  self.prepareLayout();
                }, 200 );
            });

        },

        findPanelObject: function() { 
            var panel = this.$elem;

            while ( panel.parent() !== undefined ) {
                panel = panel.parent();
                if ( panel.parent().hasClass('bn') ) { 
                    return panel.parent();
                }
            }

            return undefined;
        },

        buildNavigation: function() { 
            var panel = this.findPanelObject();
            if( panel ) {
                var nav = '<ul class="pagination " style="margin: 0px;">' +
                             '<li><a href="#" class="prev"><span class="glyphicon glyphicon-chevron-down"></span></a></li>' +
                             '<li><a href="#" class="next"><span class="glyphicon glyphicon-chevron-up"></span></a></li>' +
                           '</ul><div class="clearfix"></div>';


                var footer = $(panel).find(".bn-footer")[0];
                if( footer ) {
                    $(footer).append(nav);
                } else {
                    $(".breaking-news").append('<div class="col-xs-12 col-sm-3 bn-nav">' + nav + '</div>');
                }

                var self = this;
                $(".breaking-news").find('.prev').on('click', function(ev){
                    ev.preventDefault();
                    self.onPrev();
                });

                $(".breaking-news").find('.next').on('click', function(ev){
                    ev.preventDefault();
                    self.onNext();
                });

            }
        },

        onStop: function() {
            
        },

        onPause: function() {
            var self = this;
            self.isHovered = true;
            if(this.options.autoplay && self.timer) {
                clearTimeout(self.timer);
            }
        },

        onReset: function(status) {
            var self = this;
            if(self.timer) {
                clearTimeout(self.timer);
            }

            if(self.options.autoplay) {
                self.isHovered = status;
                self.animate();
            }
        },

        animate: function() {
            var self = this;
            self.timer = setTimeout(function() {
                
                if ( !self.options.pauseOnHover ) {
                    self.isHovered = false;
                }

                if (! self.isHovered) {
                     if(self.options.direction === 'up') {
                        self.onNext();
                     } else {
                        self.onPrev();
                     }
                } 
            }, self.options.newsTickerInterval);
        },

        onPrev: function() {
            
            var self = this;

            if ( self.animationStarted ) {
                return false;
            }

            self.animationStarted = true;

            var html = '<' + self.newsTagName + ' style="display:none;" class="' + self.newsClassName + '">' + $(self.$elem).find(self.newsTagName).last().html() + '</' + self.newsTagName + '>';
            $(self.$elem).prepend(html);
            $(self.$elem).find(self.newsTagName).first().slideDown(self.options.animationSpeed, function(){
                $(self.$elem).find(self.newsTagName).last().remove();
            });

            $(self.$elem).find(self.newsTagName +':nth-child(' + parseInt(self.options.newsPerPage + 1) + ')').slideUp(self.options.animationSpeed, function(){
                self.animationStarted = false;
                self.onReset(self.isHovered);
            });

            $(self.elem).find('.'+self.newsClassName).on('mouseenter', function(){
                self.onReset(true);
            });

            $(self.elem).find('.'+self.newsClassName).on('mouseout', function(){
                self.onReset(false);
            });
        },

        onNext: function() {
            var self = this;

            if ( self.animationStarted ) {
                return false;
            }

            self.animationStarted = true;

            var html = '<' + self.newsTagName + ' style="display:none;" class=' + self.newsClassName + '>' + $(self.$elem).find(self.newsTagName).first().html() + '</' + self.newsTagName + '>';
            $(self.$elem).append(html);

            $(self.$elem).find(self.newsTagName).first().slideUp(self.options.animationSpeed, function(){
                $(this).remove();
            });

            $(self.$elem).find(self.newsTagName +':nth-child(' + parseInt(self.options.newsPerPage + 1) + ')').slideDown(self.options.animationSpeed, function(){
                self.animationStarted = false;
                self.onReset(self.isHovered);
            });

            $(self.elem).find('.'+self.newsClassName).on('mouseenter', function(){
                self.onReset(true);
            });

            $(self.elem).find('.'+self.newsClassName).on('mouseout', function(){
                self.onReset(false);
            });
        }
    };

    $.fn.bootstrapNews = function ( options ) {
        //enable multiple DOM object selection (class selector) + enable chaining like $(".class").bootstrapNews().chainingMethod()
        return this.each( function () {

            var newsBox = Object.create( NewsBox );

            newsBox.init( options, this );
            //console.log(newsBox);

        });
    };

    $.fn.bootstrapNews.options = {
        newsPerPage: 4,
        navigation: true,
        autoplay: true,
        direction:'up',
        animationSpeed: 'normal',
        newsTickerInterval: 4000, //4 secs
        pauseOnHover: true,
        onStop: null,
        onPause: null,
        onReset: null,
        onPrev: null,
        onNext: null,
        onToDo: null
    };

})(jQuery, window, document);
$(document).ready(function(){
	
	
	
	//Reply 
	$("a.fa-reply").click(function(){
		$(".reply-form").hide();
		$("<form action='' method='POST' class='text-right reply-form'><textarea class='form-control' rows='5' placeholder='Enter your reply' name='enter-reply' id='enter-reply'></textarea><br><span class='pull-left'><span class='remaining-chars'>500 </span>characters left</span><input type='submit' value='Reply' class='btn btn-default pull-right'></form>").insertAfter($(this).closest('.comment, .reply'));
			
			$(this).hide();
			
			return false;
			
	});
	//Restrict comment and reply length
	function restrictCommentLength(elem){
		var maxChars = 500;
		
		var commentLength = elem.val().length;
		if(commentLength > maxChars){
			elem.val(elem.val().substring(0,maxChars));
			elem.closest('form').find('.remaining-chars').text(0);
		}else{
			elem.closest('form').find('.remaining-chars').text(maxChars - commentLength);
		}
	}
	$("#enter-comment").keyup(function(){
		restrictCommentLength($(this));
	}).on('paste',function(){
		setTimeout(function() {
			restrictCommentLength($("#enter-comment"));
		}, 300);
		
	});
	
	$(document).on('keyup', '#enter-reply', function(){
		restrictCommentLength($("#enter-reply"));
	}).on('paste','#enter-reply',function(){
		setTimeout(function() {
			restrictCommentLength($("#enter-reply"));
		}, 300);
	})
	 
	
	
	//Show hidden replies
	$('.show-replies').click(function(){
	$(this).prevUntil('.comment','.hidden').removeClass('hidden');
	$(this).fadeOut('fast');
	});
	
	
	
});
