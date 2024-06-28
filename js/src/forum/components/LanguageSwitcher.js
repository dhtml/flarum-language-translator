import app from 'flarum/forum/app';
import Component from 'flarum/common/Component';

export default class LanguageSwitcher extends Component {
  view() {
    const languages = {
      en: 'English',
      es: 'Spanish',
      fr: 'French'
    };

    return m('div', { className: 'LanguageSwitcher' },
      Object.keys(languages).map(locale =>
        m('button', {
          onclick: () => {
            app.session.user.savePreferences({ locale });
            window.location.reload();
          }
        }, languages[locale])
      )
    );
  }
}
