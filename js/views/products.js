/**
 *
 */
App.ProductsView = Ember.View.extend(
{
  singleView: Ember.View.extend(
  {
    templateName: 'product'
  }),
  /**
   *
   */
  gridView: Ember.View.extend(
  {
    templateName: 'products-grid',
    didInsertElement: function()
    {
      Ember.$('div.product-grid').on('scroll', Ember.$.proxy(this.didScroll, this));
    },
    willDestroyElement: function()
    {
      Ember.$('div.product-grid').off('scroll', Ember.$.proxy(this.didScroll, this));
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
      var gridHeight = Ember.$('div.product-grid').outerHeight();
      var gridScrollHeight = Ember.$('div.product-grid')[0].scrollHeight;
      var gridScrollTop = Ember.$('div.product-grid').scrollTop();

      if (gridScrollTop === 0)
      {
        return false;
      }
      return (gridScrollHeight - gridScrollTop === gridHeight);
    }
  }),
});


