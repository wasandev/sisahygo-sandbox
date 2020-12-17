
<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <VueThailandAddress
        :id="field.name"
        type="district"
        class="w-full form-control form-input form-input-bordered"
        :class="errorClasses"
        :placeholder="field.name"
        v-model="value"
      />
    </template>
  </default-field>
</template>

<script>



import VueThailandAddress from 'vue-thailand-address';
import 'vue-thailand-address/dist/vue-thailand-address.css';
import { FormField, HandlesValidationErrors } from "laravel-nova";


export default {
  name: "district",
    components: {
    VueThailandAddress,

  },
  mixins: [FormField, HandlesValidationErrors],

  props: ["resourceName", "resourceId", "field"],
  data: function() {
    return {
      district: "",
      amphoe: "",
      province: "",
      zipcode: ""
    };
  },


  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || "";
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, this.value || "");
    },

    /**
     * Update the field's internal value.
     */
    handleChange(value) {
      this.value = value;
    },
    select: function(addressData) {
      this.field.addressObject.forEach(element => {
        if (addressData.hasOwnProperty(element)) {
          Nova.$emit("address-metadata-update-" + element, {
            value: addressData[element]
          });
        }
      });
    }
  },
  mounted() {
    Nova.$on(
      "address-metadata-update-" + this.field.addressValue,
      ({ value }) => {
        this.value = value;
      }
    );
  }
};
</script>
