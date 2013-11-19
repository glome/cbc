/**
 *
 */
App.ProgramsView = Ember.View.extend(Ember.ViewTargetActionSupport,
{
  tagName: 'div',
  templateName: 'programs',
  classNames: ['row', 'programs'],
  actions:
  {
    toggle: function()
    {
      console.log('toggle programs');
    },
  }
});
