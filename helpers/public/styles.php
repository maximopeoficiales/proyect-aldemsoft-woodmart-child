<style>
    .aldem-text-white {
        color: white !important;
    }

    /* arreglos de datatable */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0 !important;
    }

    .dataTables_wrapper .dataTables_filter input {
        padding: 0 !important;
    }

    .custom-select {
        display: inline-block !important;
        width: 100% !important;
        height: calc(2.25rem + 2px) !important;
        padding: 0.375rem 1.75rem 0.375rem 0.75rem !important;
        /* font-size: 0.8rem !important; */
    }

    .custom-select-sm {
        font-size: 100%;
    }

    /* fin de arreglos */
    /* ARREGLOS DE BOOSTRAP */
    .modal-content {
        border: 0;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="search"],
    input[type="number"],
    input[type="url"],
    input[type="tel"],
    input[type="date"],
    select,
    textarea {
        border-radius: 5px !important;
    }

    /* 
.form-control-sm,
.input-group-sm > .form-control,
.input-group-sm > .input-group-append > .btn,
.input-group-sm > .input-group-append > .input-group-text,
.input-group-sm > .input-group-prepend > .btn,
.input-group-sm > .input-group-prepend > .input-group-text {
  padding: 1.1rem 0.5rem !important;
  font-size: 0.875rem;
  line-height: 1.5;
  border-radius: 0.2rem;
  height: calc(1.8125rem + 2px) !important;
} */
    .modal {
        margin-top: 10px !important;
        padding-right: 2px !important;
    }

    /* spinner */
    .lds-roller {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;
    }

    .lds-roller div {
        animation: lds-roller 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        transform-origin: 40px 40px;
    }

    .lds-roller div:after {
        content: " ";
        display: block;
        position: absolute;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #98ddca;
        margin: -4px 0 0 -4px;
    }

    .lds-roller div:nth-child(1) {
        animation-delay: -0.036s;
    }

    .lds-roller div:nth-child(1):after {
        top: 63px;
        left: 63px;
    }

    .lds-roller div:nth-child(2) {
        animation-delay: -0.072s;
    }

    .lds-roller div:nth-child(2):after {
        top: 68px;
        left: 56px;
    }

    .lds-roller div:nth-child(3) {
        animation-delay: -0.108s;
    }

    .lds-roller div:nth-child(3):after {
        top: 71px;
        left: 48px;
    }

    .lds-roller div:nth-child(4) {
        animation-delay: -0.144s;
    }

    .lds-roller div:nth-child(4):after {
        top: 72px;
        left: 40px;
    }

    .lds-roller div:nth-child(5) {
        animation-delay: -0.18s;
    }

    .lds-roller div:nth-child(5):after {
        top: 71px;
        left: 32px;
    }

    .lds-roller div:nth-child(6) {
        animation-delay: -0.216s;
    }

    .lds-roller div:nth-child(6):after {
        top: 68px;
        left: 24px;
    }

    .lds-roller div:nth-child(7) {
        animation-delay: -0.252s;
    }

    .lds-roller div:nth-child(7):after {
        top: 63px;
        left: 17px;
    }

    .lds-roller div:nth-child(8) {
        animation-delay: -0.288s;
    }

    .lds-roller div:nth-child(8):after {
        top: 56px;
        left: 12px;
    }

    @keyframes lds-roller {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* fin de spinner */
    .btn-aldem-verde {
        background-color: #98ddca;
        color: white;
        border-radius: 5px;
    }

    .bg-aldem-secondary {
        background-color: #02475e;
        color: white;
    }

    .iti.iti--allow-dropdown {
        width: 100%;
    }
    .aldem_pointer{
        cursor: pointer;
    }
</style>