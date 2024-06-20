import app from 'flarum/forum/app';

app.initializers.add('dhtml/flarum-language-translator', () => {
  console.log('[dhtml/flarum-language-translator] Hello, forum!');

  if (app.data && app.data.locale) {
    var currentLocale = app.data.locale;
    console.log('Current Locale:', currentLocale);
  } else {
    console.error('Locale is not defined.');
  }

});
