/**
 *
 */
App.Sync = DS.Model.extend(
{
  kind: DS.attr('string'),
  code:  DS.attr('string'),
  created_at: DS.attr('date'),
  expires_at: DS.attr('date')
});