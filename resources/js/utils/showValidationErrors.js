import {error} from "toastr";

export default (errors) => {
    return Object.keys(errors).forEach(key => error(errors[key][0]));
}
