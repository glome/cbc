/**
 *
 */
App.ProductsView = Ember.View.extend(
{
  /**
   *
   */
  singleView: Ember.View.extend(
  {
    templateName: 'single',
    classNames: ['single'],
  }),
  /**
   *
   */
  gridView: Ember.View.extend(
  {
    templateName: 'grid',
    classNames: ['grid'],
  }),
  /**
   *
   */
  didInsertElement: function()
  {
    Ember.$(document).on('scroll', Ember.$.proxy(this.didScroll, this));
  },
  willDestroyElement: function()
  {
    Ember.$(document).off('scroll', Ember.$.proxy(this.didScroll, this));
  },
  didScroll: function()
  {
    if (this.get('controller').get('currentProduct') == null && this.atBottom())
    {
      this.get('controller').send('getMore');
    }
  },
  atBottom: function()
  {
    var dist = Ember.$(document).height() - Ember.$(window).height();
    var scroll = Ember.$(document).scrollTop();

    if (scroll === 0)
    {
      return false;
    }
    return (scroll >= (dist - 1200));
  }
});
