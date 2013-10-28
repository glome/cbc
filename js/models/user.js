/**
 *
 */
App.User = DS.Model.extend(
{
  glomeid: DS.attr('string'),
  status: DS.attr('boolean'),
  xcsrf: DS.attr('string'),
  didLoad: function(event)
  {
    console.log(event);

    if (this.get('status'))
    {
      console.log('we have a current user: ' + this.get('id'));
    }
  }
});