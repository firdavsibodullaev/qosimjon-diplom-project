import toastr from "toastr";

export default (errors) => {

    return Object.keys(errors).forEach(key => toastr.error(errors[key][0]));
}
