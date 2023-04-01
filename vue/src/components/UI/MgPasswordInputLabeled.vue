<template>
    <label>
        <span><slot></slot></span>
        <div class="relative">
            <input
                :value="modelValue"
                @input="updateInput"
                :type="passwordFieldType"
                class="block px-3 py-2 border border-gray-300 placeholder-gray-500 dark:bg-gray-900 dark:text-white
                    text-gray-900 sm:text-sm
                    rounded-md
                    focus:outline-none focus:ring-indigo-500 focus:border-indigo-500
                    w-full
                "
                :class="classes"
                :placeholder="placeholder"

                readonly
                onfocus="this.removeAttribute('readonly');"

                autocomplete="new-password"
            >
            <eye-icon
                v-if="!passwordEyeOpened"
                class="absolute right-2 top-2.5 w-4 h-4 cursor-pointer"
                @click="toggleInputType"
                :class="eye_icon_class"
            />
            <eye-off-icon
                v-else
                class="absolute right-2 top-2.5 w-4 h-4 cursor-pointer"
                @click="toggleInputType"
                :class="eye_off_icon_class"
            />
        </div>
    </label>
</template>

<script>
import { EyeIcon, EyeOffIcon } from "@heroicons/vue/solid"

export default {
    name: 'mg-password-input-labeled',
    components: {
        EyeIcon, EyeOffIcon,
    },
    props: {
        modelValue: {
            type: [String, Number],
        },
        placeholder: {
            type: [String, Number],
        },
        classes: {
            type: String,
        },
        emits: ['update:modelValue'],
        eye_icon_class: {
            type: String,
            default: '',
        },
        eye_off_icon_class: {
            type: String,
            default: '',
        },
    },
    data(){
        return {
            passwordFieldType: '',
        }
    },
    methods: {
        updateInput(event){
            this.$emit('update:modelValue', event.target.value)
        },
        toggleInputType(){
            this.passwordFieldType = this.passwordFieldType === 'password' ? 'text' : 'password';
        }
    },
    computed: {
        passwordEyeOpened(){
            return this.passwordFieldType === 'text';
        }
    },

    mounted() {
        this.passwordFieldType = 'password';
        //this.passwordFieldType = 'text';

        //console.log('eye_icon_class:', this.eye_icon_class);
        //console.log('eye_off_icon_class:', this.eye_off_icon_class);
    }
}
</script>

<style scoped>
</style>
