/**
 *
 */
App.SearchView = Ember.View.extend(
{
  tagName: 'div',
  templateName: 'search',
  classNames: ['row', 'search'],
  actions:
  {
    click: function()
    {
      console.log('clicked search');
    },
  }
});
