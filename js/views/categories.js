/**
 *
 */
App.CategoriesView = Ember.View.extend(Ember.ViewTargetActionSupport,
{
  tagName: 'div',
  templateName: 'categories',
  classNames: ['list-group-item', 'categories', 'cm-sm-3'],
  actions:
  {
    click: function(cat)
    {
      Ember.$('div.product-grid').scrollTop(0);
      // previously active category
      var prev = Ember.$('.list-group-item.active').first();
      // current category (was just clicked)
      var curr = Ember.$('.list-group-item[data-catid="' + cat.get('id') + '"]').first();

      if (! curr.is(prev))
      {
        prev.toggleClass('active');
      }

      if (curr.parent().hasClass('categories'))
      {
        if (! curr.is(prev))
        {
          // hide children of previous active category
          prev.children('.subcategories').toggleClass('hidden');
        }
        // show subs of current active category
        curr.children('.subcategories').toggleClass('hidden');
      }

      if (! curr.hasClass('active'))
      {
        // set current category to active (change background)
        curr.toggleClass('active');
      }

      this.get('controller').get('controllers.products').send('loadProducts', cat);
    },
  }
});
