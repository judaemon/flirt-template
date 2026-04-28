import { Form, Head } from "@inertiajs/react";
import InputError from "@/components/input-error";
import PasswordInput from "@/components/password-input";
import TextLink from "@/components/text-link";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Spinner } from "@/components/ui/spinner";
import { login } from "@/routes";
import { store } from "@/routes/register";

export default function Register() {
  return (
    <>
      <Head title="Register" />
      <Form
        {...store.form()}
        resetOnSuccess={["password", "password_confirmation"]}
        disableWhileProcessing
        className="flex flex-col gap-6"
      >
        {({ processing, errors }) => (
          <>
            <div className="grid gap-6">
              <div className="grid gap-2">
                <Label htmlFor="first_name">First Name</Label>
                <Input
                  id="first_name"
                  type="text"
                  required
                  autoFocus
                  autoComplete="given-name"
                  name="first_name"
                  placeholder="First name"
                />
                <InputError message={errors.first_name} className="mt-2" />
              </div>

              <div className="grid gap-2">
                <Label htmlFor="last_name">Last Name</Label>
                <Input
                  id="last_name"
                  type="text"
                  required
                  autoComplete="family-name"
                  name="last_name"
                  placeholder="Last name"
                />
                <InputError message={errors.last_name} className="mt-2" />
              </div>

              <div className="grid gap-2">
                <Label htmlFor="company_email">Email address</Label>
                <Input
                  id="company_email"
                  type="email"
                  required
                  autoComplete="email"
                  name="company_email"
                  placeholder="email@example.com"
                />
                <InputError message={errors.company_email} />
              </div>

              <div className="grid gap-2">
                <Label htmlFor="password">Password</Label>
                <PasswordInput
                  id="password"
                  required
                  autoComplete="new-password"
                  name="password"
                  placeholder="Password"
                />
                <InputError message={errors.password} />
              </div>

              <div className="grid gap-2">
                <Label htmlFor="password_confirmation">Confirm password</Label>
                <PasswordInput
                  id="password_confirmation"
                  required
                  autoComplete="new-password"
                  name="password_confirmation"
                  placeholder="Confirm password"
                />
                <InputError message={errors.password_confirmation} />
              </div>

              <Button type="submit" className="mt-2 w-full" data-test="register-user-button">
                {processing && <Spinner />}
                Create account
              </Button>
            </div>

            <div className="text-center text-sm text-muted-foreground">
              Already have an account? <TextLink href={login()}>Log in</TextLink>
            </div>
          </>
        )}
      </Form>
    </>
  );
}

Register.layout = {
  title: "Create an account",
  description: "Enter your details below to create your account",
};
