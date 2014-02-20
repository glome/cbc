/**
 *
 */
App.Earning = DS.Model.extend(
{
  failed: DS.attr('raw'),
  fresh: DS.attr('raw'),
  paid: DS.attr('raw'),
  pending: DS.attr('raw'),
  transferred: DS.attr('raw'),
  /**
   *
   */
  formattedFresh: function()
  {
    var ret = '0,00';
    // TODO: get rid of the hard coded EUR
    if (this.get('fresh'))
    {
      var money = (parseFloat(this.get('fresh')['total']['EUR'], 10) / 100).toFixed(2);
      // Todo: do not hardocde this; get it from the server; minimum amount to redeem is 1 EUR
      (money > 0) ? ret = money.replace(/\./, ',') : ret = ret;
    }
    return ret;
  }.property('fresh'),
  /**
   *
   */
  didLoad: function(event)
  {
    console.log('we have the earnings');
  }
});

/**
 * Fetch wiki pages
 */
App.EarningAdapter = App.Adapter.extend(
{
  buildURL: function(type, glomeid)
  {
    return this.host + '/' + this.namespace + '/users/' + glomeid + '/earnings.json';
  },
});

/**
 *
 */
App.EarningSerializer = App.Serializer.extend({});
