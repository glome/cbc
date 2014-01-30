"use strict";

jQuery(document).ready(function()
{
  /******** Tabs
  jQuery('#tabs a').tabs();**********/

  /******** Category Accordion **********/
  Ember.$('#cat_accordion').cutomAccordion(
  {
    classExpand : 'custom_id20',
    menuClose: false,
    autoClose: true,
    saveState: false,
    disableLink: false,
    autoExpand: false
  });


  /******** Accordion **********/
  Ember.$('.accordion-heading, .checkout-heading').on('click', function()
  {
    Ember.$('.accordion-content, .checkout-content').slideUp('slow');
    Ember.$(this).parent().find('.accordion-content, .checkout-content').slideDown('slow');
  });

  /*`Scroll to top */
  Ember.$(function ()
  {
    Ember.$(window).scroll(function ()
    {
      if (Ember.$(this).scrollTop() > 150)
      {
        Ember.$('#back-top').fadeIn();
      } else {
        Ember.$('#back-top').fadeOut();
      }
    });
  });

  Ember.$('.backtotop').click(function()
  {
    Ember.$('html, body').animate({scrollTop: 0}, 'slow');
  });

  /******** Navigation Menu ********/
  Ember.$('nav.menu span.title').on('mouseover', function()
  {
    alert('hu');
  });

  Ember.$('nav.menu span.title').on('click', function()
  {
    alert(1);
    Ember.$(this).toggleClass("active");
    Ember.$(this).parent().find("> ul").slideToggle('medium');
  });

  Ember.$('.menu.m-menu > ul > li.categories > div > .column > div').before('<span class="more"></span>');
  Ember.$('span.more').click(function()
  {
    Ember.$(this).next().slideToggle('fast');
    Ember.$(this).toggleClass('plus');
  });

  /******** Footer Link ********/
  Ember.$("#footer h3").click(function()
  {
    $screensize = Ember.$(window).width();
    if ($screensize < 801)
    {
      Ember.$(this).toggleClass("active");
      Ember.$(this).parent().find("ul").slideToggle('medium');
    }
  });

  /******** plus minus button in qty ********/
  Ember.$(".qtyBtn").click(function()
  {
    if (Ember.$(this).hasClass("plus"))
    {
      var qty = Ember.$("#qty").val();
      qty++;
      Ember.$("#qty").val(qty);
    }
    else
    {
      var qty = Ember.$("#qty").val();
      qty--;
      if (qty>0){
        Ember.$("#qty").val(qty);
      }
    }
  });

  /******** Menu Show Hide Sub Menu ********/
  Ember.$('.menu > ul > li').mouseover(function()
  {
    Ember.$(this).find('> div').css('display', 'block');
    Ember.$(this).bind('mouseleave', function()
    {
      Ember.$(this).find('> div').css('display', 'none');
    });
  });

  /******** Mega Menu **********/
  Ember.$('.menu ul > li > a + div').each(function(index, element)
  {
    // IE6 & IE7 Fixes
    if ($.browser.msie && ($.browser.version == 7 || $.browser.version == 6))
    {
      var category = Ember.$(element).find('a');
      var columns = Ember.$(element).find('ul').length;

      Ember.$(element).css('width', (columns * 143) + 'px');
      Ember.$(element).find('ul').css('float', 'left');
    }

    var menu = Ember.$('.menu').offset();
    var dropdown = Ember.$(this).parent().offset();

    i = (dropdown.left + Ember.$(this).outerWidth()) - (menu.left + Ember.$('.menu').outerWidth());

    if (i > 0)
    {
      Ember.$(this).css('margin-left', '-' + (i + 5) + 'px');
    }
  });
});
