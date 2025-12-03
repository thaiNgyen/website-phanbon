import splitfolders, os
src = r"data\rocole\images"   # ? ch?nh l?i n?u khác
out = r"data"
os.makedirs(out, exist_ok=True)
splitfolders.ratio(src, output=out, seed=1337, ratio=(.8, .2))
print("? Split xong!")
