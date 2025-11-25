/**
 *  Pages Authentication (Login)
 */

"use strict";

const formAuthentication = document.querySelector("#formAuthentication");

document.addEventListener("DOMContentLoaded", function () {
    if (!formAuthentication) return;

    // Initialize FormValidation
    const fv = FormValidation.formValidation(formAuthentication, {
        fields: {
            email: {
                validators: {
                    notEmpty: { message: "Please enter your email" },
                    emailAddress: {
                        message: "Please enter a valid email address",
                    },

                    // Custom validator for Laravel server errors
                    laravelError: {
                        message: "", // message will be inserted later
                        callback: function () {
                            return true; // Always valid unless backend injects error
                        },
                    },
                },
            },
            password: {
                validators: {
                    notEmpty: { message: "Please enter your password" },
                    stringLength: {
                        min: 6,
                        message: "Password must be at least 6 characters",
                    },

                    // Custom validator for backend password errors
                    laravelError: {
                        message: "",
                        callback: function () {
                            return true;
                        },
                    },
                },
            },
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger({
                event: "blur", // Validate only when leaving field
            }),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                eleValidClass: "",
                rowSelector: ".mb-6",
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            autoFocus: new FormValidation.plugins.AutoFocus(),
            defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        },
        init: (instance) => {
            instance.on("plugins.message.placed", function (e) {
                if (e.element.parentElement.classList.contains("input-group")) {
                    e.element.parentElement.insertAdjacentElement(
                        "afterend",
                        e.messageElement
                    );
                }
            });
        },
    });

    // -----------------------------
    // Inject Laravel backend errors
    // -----------------------------
    if (window.laravelErrors) {
        // Email server-side error
        if (window.laravelErrors.email?.length > 0) {
            fv.updateValidatorOption(
                "email",
                "laravelError",
                "message",
                window.laravelErrors.email[0]
            );

            fv.updateFieldStatus("email", "Invalid", "laravelError");
        }

        // Password server-side error
        if (window.laravelErrors.password?.length > 0) {
            fv.updateValidatorOption(
                "password",
                "laravelError",
                "message",
                window.laravelErrors.password[0]
            );

            fv.updateFieldStatus("password", "Invalid", "laravelError");
        }
    }
});
