import app from "flarum/app";
import ExtensionPage from "flarum/admin/components/ExtensionPage";

app.initializers.add("dhtml-flarum-language-translator", () => {

  console.log('[dhtml/language-translator] Hello, forum translator admin!');

  app.extensionData.for('dhtml-language-translator').registerSetting({
    setting: 'dhtml-language-translator.googleKey',
    label: app.translator.trans('dhtml-language-translator.admin.settings.googleApiKey'),
    name: "googleAPiKey",
    type: 'text',
    required: true,
    help: 'Get your google api key from https://console.cloud.google.com',
  },15);

});
