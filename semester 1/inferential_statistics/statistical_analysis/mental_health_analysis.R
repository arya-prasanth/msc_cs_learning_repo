
# ============================================================
# INFERENTIAL STATISTICS - COMPONENT 02
# Statistical Analysis of Student Mental Health Data Using R
# ============================================================


# ------------------------------------------------------------
# 1. IMPORTING DATASET
# ------------------------------------------------------------

# Read the CSV file
# Keep the downloaded CSV in the data folder if possible.

if (file.exists("data/Student Mental health.csv")) {
  data <- read.csv("data/Student Mental health.csv")
} else {
  data <- read.csv(file.choose())
}

# Display dataset
View(data)

# Inspect dataset
head(data)
dim(data)
names(data)
str(data)

# Summary and missing values
summary(data)
colSums(is.na(data))

# Depression response frequency
table(data$Do.you.have.Depression.)


# ------------------------------------------------------------
# 2. ONE-SAMPLE PROPORTION Z-TEST
# ------------------------------------------------------------

cat("\n========== ONE-SAMPLE PROPORTION Z-TEST ==========\n")

# Count valid responses
responses <- data$Do.you.have.Depression.

n <- sum(!is.na(responses))
x <- sum(responses == "Yes", na.rm = TRUE)

# Hypothesised population proportion
p0 <- 0.50

# Calculate sample proportion
p_hat <- x / n

# Calculate Z statistic
z <- (p_hat - p0) / sqrt(p0 * (1 - p0) / n)

# Calculate two-tailed p-value
p_value <- 2 * pnorm(-abs(z))

# Display results
cat("Valid responses:", n, "\n")
cat("Depression = Yes:", x, "\n")
cat("Sample proportion:", p_hat, "\n")
cat("Z statistic:", z, "\n")
cat("P-value:", p_value, "\n")

if (p_value < 0.05) {
  cat("Reject the Null Hypothesis\n")
} else {
  cat("Fail to Reject the Null Hypothesis\n")
}


# ------------------------------------------------------------
# 3. INDEPENDENT TWO-SAMPLE WELCH T-TEST
# ------------------------------------------------------------

cat("\n========== INDEPENDENT TWO-SAMPLE T-TEST ==========\n")

# Separate students based on depression responses
depression_yes <- data$Age[
  data$Do.you.have.Depression. == "Yes"
]

depression_no <- data$Age[
  data$Do.you.have.Depression. == "No"
]

# Remove missing values
depression_yes <- na.omit(depression_yes)
depression_no <- na.omit(depression_no)

# Display group information
cat("Group size (Yes):", length(depression_yes), "\n")
cat("Group size (No):", length(depression_no), "\n")

cat("Mean age (Yes):", mean(depression_yes), "\n")
cat("Mean age (No):", mean(depression_no), "\n")

# Perform Welch Two Sample t-test
t_result <- t.test(depression_yes, depression_no)

# Display test output
print(t_result)

if (t_result$p.value < 0.05) {
  cat("Reject the Null Hypothesis\n")
} else {
  cat("Fail to Reject the Null Hypothesis\n")
}


# ------------------------------------------------------------
# 4. REGRESSION ANALYSIS: AGE AND CGPA
# ------------------------------------------------------------

cat("\n========== REGRESSION ANALYSIS ==========\n")

# Extract CGPA ranges
cgpa <- trimws(data$What.is.your.CGPA.)

# Convert CGPA ranges into numerical midpoints
cgpa_num <- sapply(cgpa, function(x) {

  values <- as.numeric(
    unlist(regmatches(
      x,
      gregexpr("[0-9]+\\.?[0-9]*", x)
    ))
  )

  if (length(values) == 2) {
    mean(values)
  } else {
    NA_real_
  }
})

# Create regression dataset
reg_data <- data.frame(
  Age = data$Age,
  CGPA = cgpa_num
)

# Remove incomplete observations
reg_data <- na.omit(reg_data)

# Fit linear regression model
model <- lm(CGPA ~ Age, data = reg_data)

# Display regression summary
summary(model)

# Display regression equation
cat("Intercept:", coef(model)[1], "\n")
cat("Age coefficient:", coef(model)[2], "\n")

cat(
  "Regression equation: CGPA =",
  coef(model)[1], "+",
  coef(model)[2], "* Age\n"
)

# Save regression scatter plot
if (!dir.exists("plots")) {
  dir.create("plots")
}

png("plots/regression_plot.png",
    width = 900, height = 600)

plot(
  reg_data$Age,
  reg_data$CGPA,
  main = "Regression Analysis: Age vs CGPA",
  xlab = "Age",
  ylab = "Approximate CGPA",
  pch = 19
)

abline(model, col = "red", lwd = 2)

dev.off()


# ------------------------------------------------------------
# 5. PEARSON CORRELATION ANALYSIS
# ------------------------------------------------------------

cat("\n========== PEARSON CORRELATION ANALYSIS ==========\n")

# Perform Pearson correlation test
cor_result <- cor.test(
  reg_data$Age,
  reg_data$CGPA,
  method = "pearson"
)

# Display correlation results
print(cor_result)

# Save correlation scatter plot
png("plots/correlation_plot.png",
    width = 900, height = 600)

plot(
  reg_data$Age,
  reg_data$CGPA,
  main = "Correlation Analysis: Age vs CGPA",
  xlab = "Age",
  ylab = "Approximate CGPA",
  pch = 19,
  col = "blue"
)

dev.off()


# ------------------------------------------------------------
# 6. FINAL SUMMARY
# ------------------------------------------------------------

cat("\n========== FINAL ANALYSIS SUMMARY ==========\n")

cat("\nZ-Test P-value:", p_value)
cat("\nT-Test P-value:", t_result$p.value)
cat("\nRegression P-value:",
    summary(model)$coefficients[2, 4])
cat("\nCorrelation Coefficient:", cor_result$estimate)
cat("\nCorrelation P-value:", cor_result$p.value)

cat("\n\nAnalysis completed successfully!\n")
cat("Plots saved inside the plots folder.\n")

# ============================================================
# END OF PROJECT
# ============================================================
