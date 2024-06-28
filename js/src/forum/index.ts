import app from 'flarum/forum/app';
import addLanguageMenu from "./includes/addLanguageMenu";

app.initializers.add('dhtml/flarum-language-translator', () => {
  addLanguageMenu();
});
