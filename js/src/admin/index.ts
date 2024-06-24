import app from "flarum/app";
import ExtensionPage from "flarum/admin/components/ExtensionPage";

app.initializers.add('dhtml-flarum-language-translator', () => {
  console.log('[dhtml/flarum-language-translator] Hello, flarum t admin!');

  app.extensionData.for('dhtml-flarum-language-translator').registerSetting({
    setting: 'dhtml-flarum-language-translator.googleKey',
    label: app.translator.trans('dhtml-flarum-language-translator.admin.settings.googleKey'),
    type: 'text',
    help: 'Enter your Google API Key',
  },15);
});
