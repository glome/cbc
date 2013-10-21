/**
 *
 */
App.Category = DS.Model.extend(
{
  name: DS.attr('string'),
  linked_to: DS.attr('number'),
  linked_at: DS.attr('date'),
  num_published_products: DS.attr('number'),
  children: DS.attr('raw'),
  /**
   *
   */
  subcategories: function()
  {
    retval = [];
    if (this.get('children'))
    {
      retval = this.store.pushMany('category', this.get('children').filterBy('num_published_products'));
    }
    return retval;
  }.property('children'),
  /**
   *
   */
  hasProducts: function()
  {
    if (this.get('num_published_products') > 0)
    {
      return true;
    }
    else
    {
      var retval = false;
      var counters = this.get('children');
      if (counters)
      {
        counters.forEach(function(val)
        {
          if (val.num_published_products > 0)
          {
            retval = true;
          }
        });
      }
      return retval;
    }
  }.property('children', 'num_published_products'),
  /**
   *
   */
  urlName: function()
  {
    return this.get('name').replace(/\s/g, '_');
  }.property('name')
});
