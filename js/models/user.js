/**
 *
 */
App.User = DS.Model.extend(
{
  glomeid: DS.attr('string'),
  status: DS.attr('boolean'),
  xcsrf: DS.attr('string'),
  //"earnings":{"fresh":{},"pending":{},"failed":{},"paid":{},"transferred":{"EUR":110}}
  earnings: DS.attr('raw'),
  message: DS.attr('string'),
  fresh: function()
  {
    var ret = '0,00';
    // Todo: parse different currencies separately, don't hardcode EUR
    if (this.get('earnings'))
    {
      var money = (parseFloat(this.get('earnings')['fresh']['EUR'], 10) / 100).toFixed(2);
      // Todo: do not hardocde this; get it from the server; minimum amount to redeem is 1 EUR
      (money > 0) ? ret = money.replace(/\./, ',') : ret = ret;
    }
    return ret;
  }.property('earnings'),
  is_not_hello: function()
  {
    return (this.get('message') != 'Hello!');
  }.property('message'),
  didLoad: function(event)
  {
    if (this.get('status'))
    {
      console.log('we have a current user: ' + this.get('id'));
    }
  }
});