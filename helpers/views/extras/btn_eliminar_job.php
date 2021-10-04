<?php
$urlDeleteJob = get_site_url() . "/wp-json/aldem/v1/deleteJob/" . aldem_getUserNameCurrent();
// print_r($atts);
$id_job = $atts["param"];
$id_job = $_GET[$id_job];
if (empty($id_job)) return;
?>
<div class="my-2">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <button type="button" class="btn  w-100 btn-aldem-danger" id="btnEliminarJob" style="width: 60%;"><i class="fa fa-trash mx-1" aria-hidden="true"></i>Eliminar</button>

        </div>
    </div>

</div>

<script>
    (() => {
        const showSpinnerCargando = () => {
            Swal.fire({
                title: '<strong>Cargando...</strong>',
                html: `<div class='text-center'><div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>`,
                showConfirmButton: false
            })
        };
        const closeSpinnerCargando = () => {
            Swal.closeModal();
        }
        const aldemDeleteJob = async () => {
            showSpinnerCargando();
            try {
                let myHeaders = new Headers();
                myHeaders.append("Content-Type", "application/json");
                myHeaders.append("Authorization", "Bearer <?= aldem_getBearerToken() ?>");
                let raw = JSON.stringify({
                    "id_job": <?= $id_job ?>
                });
                let requestOptions = {
                    method: 'POST',
                    headers: myHeaders,
                    body: raw,
                    redirect: 'follow'
                };
                let response = await (await (fetch('<?= $urlDeleteJob  ?>', requestOptions))).json();
                closeSpinnerCargando();
                if (response.status == 200) {
                    return true;
                } else if (response.status == 404) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: `${response.data}`,
                    })
                    return false;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Ocurrio un error en Servidor!',
                    })
                    return false;
                }
            } catch (error) {
                // error en el servidor
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ocurrio un error en Servidor!',
                })
                return false;
            }
        }

        document.querySelector("#btnEliminarJob").addEventListener("click", async () => {
            let validation = await aldemDeleteJob()
            if (validation) {
                Swal.fire({
                    icon: "success",
                    title: `Eliminado correctamente`,
                    showConfirmButton: false,
                    timer: 1500
                })
                setTimeout(() => {
                    location.href = "<?= get_site_url() . "/marken_export" ?>";
                }, 1500);

            }
        });
    })()
</script>