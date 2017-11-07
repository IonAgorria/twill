<template>
  <a17-inputframe :error="error" :note="note" :label="label" :locale="locale" @localize="updateLocale">
    <span class="select__input">
      <select v-model="selectedValue" :name="name" :id="name" :disabled="disabled" :required="required" :readonly="readonly">
        <option v-for="option in options" :value="option.value" >
          {{ option.label }}
        </option>
      </select>
    </span>
  </a17-inputframe>
</template>

<script>
  import InputMixin from '@/mixins/input'
  import FormStoreMixin from '@/mixins/formStore'
  import InputframeMixin from '@/mixins/inputFrame'
  import LocaleMixin from '@/mixins/locale'

  export default {
    name: 'A17Select',
    mixins: [InputMixin, InputframeMixin, LocaleMixin, FormStoreMixin],
    props: {
      initialValue: {
        default: ''
      },
      options: {
        default: function () { return [] }
      }
    },
    data: function () {
      return {
        currentValue: this.initialValue
      }
    },
    computed: {
      selectedValue: {
        get: function () {
          return this.currentValue
        },
        set: function (value) {
          this.currentValue = value

          // see formStore mixin
          this.saveIntoStore(value)

          this.$emit('change', value)
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  @import "../../scss/setup/variables.scss";
  @import "../../scss/setup/colors.scss";
  @import "../../scss/setup/mixins.scss";

  // hightly inspired by Mike's select done in NEJM

  /*
    NB:At the top is the stock select-css with the IE11 addition from the compat CSS. At the bottom we have the NEJM specific styles
  */

  /* Container used for styling the custom select, the buttom class below adds the
   * bg gradient, corners, etc. */

  .select__input {
    display: block;
    position: relative;
  }


  /* This is the native select, we're making everything but the text invisible so
   * we can see the button styles in the wrapper */
  .select__input select {
    width: 100%;
    margin: 0;
    outline: none;
    padding: .6em .8em .5em .8em;

    /* Prefixed box-sizing rules necessary for older browsers */
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;

    /* Font size must be 16px to prevent iOS page zoom on focus */
    font-size: 16px;
  }


  /* Custom arrow sits on top of the select - could be an image, SVG, icon font,
   * etc. or the arrow could just baked into the bg image on the select. */
  .select__input::after {
    content: " ";
    position: absolute;
    top: 50%;
    right: 1em;
    z-index: 2;
    /* These hacks make the select behind the arrow clickable in some browsers */
    pointer-events: none;
    display: none;
  }

  // @supports ( -webkit-appearance: none ) or ( appearance: none ) {

    /* Show custom arrow */
    .select__input::after {
      display: block;
    }

    /* Remove select styling */
    .select__input select {
      padding-right: 2em; /* Match-01 */
      /* inside @supports so that iOS <= 8 display the native arrow */
      background: none; /* Match-04 */
      /* inside @supports so that Android <= 4.3 display the native arrow */
      border: 1px solid transparent; /* Match-05 */
      appearance: none;
      -webkit-appearance: none;
    }

    .select__input select:focus {
      //border-color: #aaa; /* Match-03 */
    }
  // }

  @supports ( -moz-appearance: none ) and ( mask-type: alpha ) {
    /* Firefox <= 34 has a false positive on @supports( -moz-appearance: none )
     * @supports ( mask-type: alpha ) is Firefox 35+
     */

     /* Show custom arrow */
     .select__input::after {
       display: block;
     }

     /* Remove select styling */
     .select__input select {
       padding-right: 2em; /* Match-01 */
       /* inside @supports so that iOS <= 8 display the native arrow */
       background: none; /* Match-04 */
       /* inside @supports so that Android <= 4.3 display the native arrow */
       border: 1px solid transparent; /* Match-05 */
       appearance: none;
     }

     .select__input select:focus {
       //border-color: #aaa; /* Match-03 */
     }
  }

  /* IE 10/11+ - This hides native dropdown button arrow so it will have the custom appearance. Targeting media query hack via http://browserhacks.com/#hack-28f493d247a12ab654f6c3637f6978d5 - looking for better ways to achieve this targeting */
  /* The second rule removes the odd blue bg color behind the text in the select button in IE 10/11 and sets the text color to match the focus style's - fix via http://stackoverflow.com/questions/17553300/change-ie-background-color-on-unopened-focused-select-box */
  @media screen and (-ms-high-contrast: active), (-ms-high-contrast: none) {
      .select__input select::-ms-expand {
        display: none;
      }

      .select__input select:focus {
        //border-color: #aaa; /* Match-03 */
      }

      .select__input select:focus::-ms-value {
        background: transparent;
        color: #222; /* Match-02*/
      }

      .select__input select {
        padding-right: 2em; /* Match-01 */
        background: none; /* Match-04 */
        border: 1px solid transparent; /* Match-05 */
      }

      .select__input::after {
        display: block;
      }
    }

  /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

  .select__input {
    border: 1px solid $color__fborder;
    background-color: $color__background;
    border-radius: 2px;
    cursor: pointer;
    height: 35px;

    &:hover {
      border-color: $color__fborder--hover;
    }

    &:focus {
      border-color: $color__fborder--hover;
    }
  }

  .select__input select {
    font-size:15px;
    line-height:15px;
    height: 35px;
    padding: 0 35px 0 14px;
    border-radius: 2px;
    color: $color__text--light;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    -webkit-padding-end: 35px !important;
    -webkit-padding-start: 14px !important;
  }

  .select__input:hover select {
    color: $color__text;
  }

  .select__input::after {
    width: 0;
    height: 0;
    margin-top: -1px;
    border-width: 4px 4px 0;
    border-style: solid;
    border-color: $color__icons transparent transparent;
  }

  .select__input:hover::after,
  .select__input:focus::after {
    border-color: $color__icons transparent transparent;
  }

  .select__input select:focus {
    outline: none;
  }

  .select__input select:disabled {
    opacity: .5;
    pointer-events: none;
  }

  .select__input option {
    font-weight: normal;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
  }
</style>