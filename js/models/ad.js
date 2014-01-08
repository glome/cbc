/**
 *
 */
App.Ad = DS.Model.extend(
{
  title: DS.attr('string'),
  description: DS.attr('string'),
  content: DS.attr('string'),
  bonus_money: DS.attr('number'),
  bonus_percent: DS.attr('number'),
  bonus_text: DS.attr('string'),
  rating: DS.attr('number'),
  /**
   *
   */
  cashback: function()
  {
    var bonusMoney = this.get('bonus_money');
    var bonusPercent = this.get('bonus_percent');
    var bonusText = this.get('bonus_text');

    var ret = 'Cashback: ';
    if (bonusMoney)
    {
      ret += bonusMoney + ' ' + this.get('currency')
      if (bonusPercent)
      {
        ret += ' & ';
      }
    }
    if (bonusPercent)
    {
      ret += bonusPercent + ' %';
    }

    return ret;
  }.property('bonus_money', 'bonus_percent', 'bonus_text')
});
