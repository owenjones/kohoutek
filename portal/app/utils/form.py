def form_input_array(dict, name):
    # Will find all inputs in the passed dict with the <input> name attribute
    # "name[key]", and return a list of (key, value) tuples
    # ~~ why don't forms support arrays yet, UGH! ~~
    matched = []
    input = f"{name}["

    for k, v in dict.items():
        if k.startswith(input):
            k = k.replace(input, "").replace("]", "")
            matched.append((k, v))

    return matched
