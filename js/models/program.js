/**
 *
 */
App.Program = DS.Model.extend(
{
  country:  DS.attr('string'),
  currency:  DS.attr('string'),
  language: DS.attr('string'),
  advertiser: DS.attr('raw'),
  first_ad: DS.attr('raw'),
  first_product: DS.attr('raw')
});
