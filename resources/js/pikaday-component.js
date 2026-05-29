import Pikaday from 'pikaday';

export default function pikadayComponent({ state, minDate, maxDate, minYear, maxYear, firstDay, displayFormat, i18n, isDisabled }) {
    return {
        state: state,
        picker: null,

        init() {
            if (isDisabled) {
                this.setDisplayValue(this.state);
                return;
            }

            const self = this;
            const inputEl = this.$refs.input;

            this.picker = new Pikaday({
                field: inputEl,
                trigger: inputEl,
                defaultDate: new Date(),
                setDefaultDate: false,
                container: this.$el,
                firstDay: firstDay || 1,
                minDate: minDate ? this.parseIso(minDate) : null,
                maxDate: maxDate ? this.parseIso(maxDate) : null,
                yearRange: [minYear || 2024, maxYear || new Date().getFullYear() + 1],
                toString: (date) => this.formatDisplay(date),
                parse: (str) => this.parseDisplay(str),
                i18n: i18n,
                theme: 'pikaday-filament',
                onSelect(date) {
                    self.state = self.toIso(date);
                },
            });

            // Remove Pikaday's focus/change listeners — input is readonly,
            // calendar opens only on click, no text input handling needed
            inputEl.removeEventListener('focus', this.picker._onInputFocus);
            inputEl.removeEventListener('change', this.picker._onInputChange);

            // Set initial display value
            if (this.state) {
                this.picker.setDate(this.parseIso(this.state), true);
            } else {
                this.picker._d = null;
                inputEl.value = '';
            }

            // Watch Livewire state changes
            this.$watch('state', (value) => {
                if (value) {
                    const date = this.parseIso(value);
                    if (!isNaN(date.getTime())) {
                        this.picker.setDate(date, true);
                    }
                } else {
                    // hide first so draw() skips the trigger.focus() call
                    this.picker.hide();
                    this.picker._d = null;
                    inputEl.value = '';
                }
            });
        },

        destroy() {
            if (this.picker) {
                this.picker.destroy();
            }
        },

        clearDate() {
            // Hide first so Pikaday's draw() skips trigger.focus() (which would reopen calendar)
            if (this.picker) {
                this.picker.hide();
            }
            this.state = null;
            // $watch('state') handles picker._d = null and inputEl.value = ''
        },

        /** Date → 'YYYY-MM-DD' */
        toIso(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        },

        /** 'YYYY-MM-DD' → Date (noon to avoid timezone issues) */
        parseIso(str) {
            const parts = str.split('-');
            return new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]), 12, 0, 0);
        },

        /** Date → display string based on displayFormat */
        formatDisplay(date) {
            const d = String(date.getDate()).padStart(2, '0');
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const y = date.getFullYear();

            return displayFormat
                .replace('DD', d)
                .replace('MM', m)
                .replace('YYYY', y);
        },

        /** Display string → Date — format-aware (DD/MM/YYYY, MM/DD/YYYY, DD.MM.YYYY, …) */
        parseDisplay(str) {
            const nums = (str.match(/\d+/g) || []).map((n) => parseInt(n, 10));
            if (nums.length >= 3) {
                // Derive token order from displayFormat so any separator works.
                const order = ['DD', 'MM', 'YYYY']
                    .map((tok) => ({ tok, pos: displayFormat.indexOf(tok) }))
                    .filter((t) => t.pos !== -1)
                    .sort((a, b) => a.pos - b.pos)
                    .map((t) => t.tok);
                const map = {};
                order.forEach((tok, i) => (map[tok] = nums[i]));
                const day = map['DD'];
                const month = (map['MM'] || 1) - 1;
                const year = map['YYYY'];
                if (!isNaN(day) && !isNaN(month) && !isNaN(year)) {
                    return new Date(year, month, day, 12, 0, 0);
                }
            }

            // Fallback: try ISO
            const iso = new Date(str + 'T12:00:00');
            if (!isNaN(iso.getTime())) {
                return iso;
            }

            return new Date();
        },

        /** Set display value on disabled input */
        setDisplayValue(isoStr) {
            if (!isoStr) return;
            try {
                const date = this.parseIso(isoStr);
                this.$refs.input.value = this.formatDisplay(date);
            } catch (e) {
                // ignore
            }
        },
    };
}
