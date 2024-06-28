import { extend } from 'flarum/common/extend';
import app from 'flarum/forum/app';
import LocaleDropdown from 'flarum/forum/components/LocaleDropdown';
import Button from 'flarum/common/components/Button';
import HeaderSecondary from 'flarum/common/components/HeaderSecondary';
import SelectDropdown from 'flarum/common/components/SelectDropdown';

export default function () {
  extend(HeaderSecondary.prototype, 'items', function (items) {
    // Clear existing items
    //items.clear();

    const languages = {
      en: 'English',
      es: 'Spanish',
      ar: 'Arabic',
      fr: 'French'
    };

    const locales = [];

    for (const locale in languages) {
      locales.push(
        <Button
          active={app.data.locale === locale}
          icon={app.data.locale === locale ? 'fas fa-check' : true}
          onclick={() => {
            alert("Switch to: "+locale);
            /*
            if (app.session.user) {
              app.session.user.savePreferences({ locale }).then(() => window.location.reload());
            } else {
              document.cookie = `locale=${locale}; path=/; expires=Tue, 19 Jan 2038 03:14:07 GMT`;
              window.location.reload();
            }
             */
          }}
        >
          {languages[locale]}
        </Button>
      );
    }


    items.add(
      'locale',
      <SelectDropdown
        buttonClassName="Button Button--link"
        accessibleToggleLabel={app.translator.trans('core.forum.header.locale_dropdown_accessible_label')}
      >
        {locales}
      </SelectDropdown>,
      20
    );

  });
}
