import { extend } from 'flarum/common/extend';
import app from 'flarum/forum/app';
import HeaderPrimary from 'flarum/forum/components/HeaderPrimary';
import LanguageSwitcher from './components/LanguageSwitcher';
import addLanguageMenu from "./includes/addLanguageMenu";

app.initializers.add('dhtml/flarum-language-translator', () => {

  //addLanguageMenu();
  /*
  extend(HeaderPrimary.prototype, 'items', items => {
    items.add('languageSwitcher', LanguageSwitcher.component(), 10);
  });
  */

  /*
  console.log('[dhtml/flarum-language-translator] Hello, forum!');

  if (app.data && app.data.locale) {
    var currentLocale = app.data.locale;
    console.log('Current Locale:', currentLocale);
  } else {
    console.error('Locale is not defined.');
  }
  */

});
