import auth from "@/middlewares/auth";
import guest from "@/middlewares/guest";

const middlewaresList = {auth, guest};

export default (middleware, options) => {
    return middlewaresList[middleware](options.to, options.from, options.next);
}
