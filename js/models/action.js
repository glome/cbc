/**
 *
 */
App.Action = DS.Model.extend(
{
  action:  DS.attr('string'),
  kind: DS.attr('string'),
  subject_id: DS.attr('number'),
  created_at: DS.attr('date')
});