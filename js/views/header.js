
/**
 *
 */
App.HeaderView = Ember.View.extend(
{
  tagName: 'div',
  role: 'navigation',
  templateName: 'header',
  attributeBindings: ['role'],
  classNames: ['navbar', 'navbar-fixed-top', 'header'],

  leftBox: Ember.View.extend(
  {
    tagName: 'li',
    classNames: ['leftbox'],
    templateName: 'leftBox',
    click: function(e)
    {
      Ember.$('.header .leftbox .type').toggleClass('pair');
      this.get('parentView').get('childViews')[1].toggleProperty('info');
      if (! Ember.$('.header .leftbox .type').hasClass('pair'))
      {
        this.get('controller').get('controllers.sync').send('getSyncCode');
      }
      return false;
    }
  }),
  centerBox: Ember.View.extend(
  {
    info: true,
    tagName: 'li',
    classNames: ['centerbox'],
    templateName: 'centerBox',
    actions:
    {
      'toggle': function()
      {
        Ember.$('.content').toggleClass('openhdr');
        Ember.$('.header, .header *').toggleClass('open');

        if (Ember.$('.header').hasClass('open'))
        {
          if (! Ember.$('.header .leftbox .type').hasClass('pair'))
          {
            this.get('controller').get('controllers.sync').send('getSyncCode');
          }
        }
        return false;
      }
    }
  })
});
