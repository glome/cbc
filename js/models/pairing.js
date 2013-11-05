/**
 *
 */
App.Pairing = DS.Model.extend(
{
  kind: DS.attr('string'),
  code_1:  DS.attr('string'),
  code_2:  DS.attr('string'),
  code_3:  DS.attr('string'),
  created_at: DS.attr('date'),
  expires_at: DS.attr('date'),
});