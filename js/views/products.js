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
    templateName: 'single'
  }),
  /**
   *
   */
  gridView: Ember.View.extend(
  {
    templateName: 'grid',
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
    if (this.atBottom())
    {
      this.get('controller').send('getMore');
    }
  },
  atBottom: function()
  {
    var dist = $(document).height() - $(window).height();
    var scroll = Ember.$(document).scrollTop();

    if (scroll === 0)
    {
      return false;
    }
    return (scroll - dist === 0);
  }
});


